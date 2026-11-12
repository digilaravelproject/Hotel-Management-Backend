<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Compress an uploaded image file, convert it to high-definition WebP format,
     * maintaining crisp quality for 4K / 64-inch TV displays while minimizing file size (KB).
     *
     * @param UploadedFile|string $file Uploaded file instance or local path
     * @param string $folder Destination directory relative to public/ (e.g. 'uploads/hotel_logos')
     * @param int $targetMaxKb Target maximum size in KB (default: 800 KB)
     * @param string $prefix File name prefix (e.g. 'logo', 'cover', 'slider', 'amenity')
     * @param int $maxDimension Maximum width or height constraint in pixels (default: 2560px for 4K/64" TV)
     * @return string Relative asset path for storage and display (e.g. 'uploads/hotel_logos/logo_1720000000_a1b2c3d4.webp')
     */
    public static function compressAndConvertToWebp(
        UploadedFile|string $file,
        string $folder = 'uploads/general',
        int $targetMaxKb = 800,
        string $prefix = 'img',
        int $maxDimension = 2560
    ): string {
        $cleanFolder = trim(str_replace('\\', '/', $folder), '/');
        // Clean leading 'storage/' if present to standardize on public/
        if (str_starts_with($cleanFolder, 'public/')) {
            $cleanFolder = substr($cleanFolder, 7);
        }

        $destinationPath = public_path($cleanFolder);

        if (!file_exists($destinationPath)) {
            @mkdir($destinationPath, 0755, true);
        }

        $safePrefix = preg_replace('/[^a-zA-Z0-9_-]/', '', $prefix) ?: 'img';
        $filename = $safePrefix . '_' . time() . '_' . Str::random(8);

        $isUploadedFile = $file instanceof UploadedFile;
        $extension = strtolower($isUploadedFile ? $file->getClientOriginalExtension() : pathinfo($file, PATHINFO_EXTENSION));
        $mime = $isUploadedFile ? $file->getClientMimeType() : (@mime_content_type($file) ?: '');

        // 1. Vector graphics (SVG): Keep original SVG format for infinite vector scaling after strict DOM sanitization
        if ($extension === 'svg' || $mime === 'image/svg+xml') {
            $svgSource = $isUploadedFile ? @file_get_contents($file->getRealPath()) : @file_get_contents($file);
            if ($svgSource === false || trim($svgSource) === '') {
                throw new \InvalidArgumentException('Uploaded SVG file is empty or unreadable.');
            }

            $sanitizedSvg = self::sanitizeSvg($svgSource);
            $savedName = $filename . '.svg';
            file_put_contents($destinationPath . DIRECTORY_SEPARATOR . $savedName, $sanitizedSvg);
            return $cleanFolder . '/' . $savedName;
        }

        // 2. Read source file content into memory and strictly validate image via GD
        $filePath = $isUploadedFile ? $file->getRealPath() : $file;
        $fileContent = @file_get_contents($filePath);

        if ($fileContent === false) {
            throw new \InvalidArgumentException('Uploaded image file cannot be read.');
        }

        $sourceImage = @imagecreatefromstring($fileContent);

        if (!$sourceImage) {
            throw new \InvalidArgumentException('Uploaded file is not a valid or supported raster image.');
        }

        // 3. Fix JPEG / Phone camera EXIF orientation if available
        if (function_exists('exif_read_data') && ($extension === 'jpg' || $extension === 'jpeg')) {
            try {
                $exif = @exif_read_data($filePath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $rotated = imagerotate($sourceImage, 180, 0);
                            imagedestroy($sourceImage);
                            $sourceImage = $rotated;
                            break;
                        case 6:
                            $rotated = imagerotate($sourceImage, -90, 0);
                            imagedestroy($sourceImage);
                            $sourceImage = $rotated;
                            break;
                        case 8:
                            $rotated = imagerotate($sourceImage, 90, 0);
                            imagedestroy($sourceImage);
                            $sourceImage = $rotated;
                            break;
                    }
                }
            } catch (\Throwable $e) {
                // Ignore EXIF read errors
            }
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // 4. Calculate dimensions - downscale only if unusually large to preserve TV sharpness without lag
        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
            if ($origWidth >= $origHeight) {
                $targetWidth = $maxDimension;
                $targetHeight = (int) round(($origHeight / $origWidth) * $maxDimension);
            } else {
                $targetHeight = $maxDimension;
                $targetWidth = (int) round(($origWidth / $origHeight) * $maxDimension);
            }
        }

        // 5. Create TrueColor canvas with full Alpha channel transparency support
        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);

        // Resample smoothly to canvas
        imagecopyresampled(
            $canvas,
            $sourceImage,
            0, 0, 0, 0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        $savedName = $filename . '.webp';
        $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $savedName;

        // 6. Encode to WebP with high initial quality (88) for 64" TV display crispness
        $quality = 88;
        $finalImageData = null;

        do {
            ob_start();
            imagewebp($canvas, null, $quality);
            $imageData = ob_get_clean();
            $fileSizeKb = strlen($imageData) / 1024;

            $finalImageData = $imageData;

            // Stop if under target size or if quality threshold reached
            if ($fileSizeKb <= $targetMaxKb || $quality <= 68) {
                break;
            }

            // Gently reduce quality to preserve pristine visual details
            $quality -= 5;
        } while ($quality >= 65);

        if ($finalImageData !== null) {
            file_put_contents($fullPath, $finalImageData);
        }

        // 7. Free GD memory resources
        imagedestroy($sourceImage);
        imagedestroy($canvas);

        return $cleanFolder . '/' . $savedName;
    }

    /**
     * Delete an existing image file from public or storage path safely.
     *
     * @param string|null $path Relative path stored in database
     * @return bool True if deleted or already non-existent, false on failure
     */
    public static function deleteFile(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $cleanPath = trim(str_replace('\\', '/', $path), '/');
        if ($cleanPath === '' || str_contains($cleanPath, '..') || str_contains($cleanPath, "\0")) {
            return false;
        }

        $publicRoot = realpath(public_path());
        $storageRoot = realpath(storage_path('app/public'));

        // Check standard public path safely within public root
        $publicFile = realpath(public_path($cleanPath));
        if ($publicFile && $publicRoot && str_starts_with($publicFile, $publicRoot . DIRECTORY_SEPARATOR) && is_file($publicFile)) {
            @unlink($publicFile);
            return true;
        }

        // Check storage symlink variants safely within storage/app/public root
        $storageRel = str_starts_with($cleanPath, 'storage/') ? substr($cleanPath, 8) : $cleanPath;
        $realStorage = realpath(storage_path('app/public/' . $storageRel));
        if ($realStorage && $storageRoot && str_starts_with($realStorage, $storageRoot . DIRECTORY_SEPARATOR) && is_file($realStorage)) {
            @unlink($realStorage);
            return true;
        }

        return false;
    }

    /**
     * Strict XML/DOM SVG sanitizer blocking active scripts, XXE/DTDs, foreignObject,
     * SMIL animation vectors, event handlers, and javascript:/data: URIs.
     */
    public static function sanitizeSvg(string $svgSource): string
    {
        if (preg_match('/<!(?:DOCTYPE|ENTITY)|<\?(?:php|=)|javascript:|vbscript:/i', $svgSource)) {
            throw new \InvalidArgumentException('Uploaded SVG contains prohibited directives or executable schemes.');
        }

        $prevUseErrors = libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $loaded = $dom->loadXML($svgSource, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($prevUseErrors);

        if (!$loaded || !$dom->documentElement || strtolower($dom->documentElement->localName ?? $dom->documentElement->nodeName) !== 'svg') {
            throw new \InvalidArgumentException('Uploaded SVG document structure is invalid.');
        }

        $disallowedTags = [
            'script', 'foreignobject', 'iframe', 'object', 'embed', 'applet',
            'meta', 'link', 'base', 'style', 'animate', 'animatecolor',
            'animatemotion', 'animatetransform', 'set', 'audio', 'video', 'handler',
        ];

        $xpath = new \DOMXPath($dom);
        foreach ($xpath->query('//*') as $node) {
            if (!($node instanceof \DOMElement)) {
                continue;
            }
            $tagName = strtolower($node->localName ?? $node->nodeName);
            if (in_array($tagName, $disallowedTags, true)) {
                $node->parentNode?->removeChild($node);
                continue;
            }

            // Inspect all attributes on remaining elements
            $attrsToRemove = [];
            foreach ($node->attributes as $attr) {
                $attrName = strtolower($attr->nodeName);
                $localAttr = strtolower($attr->localName ?? $attr->name);
                $val = html_entity_decode(trim((string) $attr->nodeValue), ENT_QUOTES | ENT_HTML5, 'UTF-8');

                if (str_starts_with($localAttr, 'on') || $localAttr === 'style') {
                    $attrsToRemove[] = $attr->nodeName;
                    continue;
                }

                if (in_array($localAttr, ['href', 'src', 'action', 'formaction'], true) || str_ends_with($attrName, ':href')) {
                    if ($val !== '' && !str_starts_with($val, '#')) {
                        $attrsToRemove[] = $attr->nodeName;
                    }
                } elseif (preg_match('/(?:javascript|vbscript|data\s*:\s*text\/html|expression\s*\()/i', $val)) {
                    $attrsToRemove[] = $attr->nodeName;
                }
            }

            foreach ($attrsToRemove as $removeName) {
                $node->removeAttribute($removeName);
            }
        }

        $cleanXml = $dom->saveXML($dom->documentElement);
        if (!$cleanXml) {
            throw new \InvalidArgumentException('Failed to serialize sanitized SVG content.');
        }

        return $cleanXml;
    }
}
