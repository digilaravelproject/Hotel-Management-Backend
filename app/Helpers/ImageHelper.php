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

        // 1. Vector graphics (SVG): Keep original SVG format for infinite vector scaling without rasterization
        if ($extension === 'svg' || $mime === 'image/svg+xml') {
            $savedName = $filename . '.svg';
            if ($isUploadedFile) {
                $file->move($destinationPath, $savedName);
            } else {
                @copy($file, $destinationPath . DIRECTORY_SEPARATOR . $savedName);
            }
            return $cleanFolder . '/' . $savedName;
        }

        // 2. Read source file content into memory
        $filePath = $isUploadedFile ? $file->getRealPath() : $file;
        $fileContent = @file_get_contents($filePath);

        if ($fileContent === false) {
            // Fallback move
            $savedName = $filename . '.' . ($extension ?: 'jpg');
            if ($isUploadedFile) {
                $file->move($destinationPath, $savedName);
            }
            return $cleanFolder . '/' . $savedName;
        }

        $sourceImage = @imagecreatefromstring($fileContent);

        if (!$sourceImage) {
            // Fallback if GD cannot parse format
            $savedName = $filename . '.' . ($extension ?: 'jpg');
            if ($isUploadedFile) {
                $file->move($destinationPath, $savedName);
            }
            return $cleanFolder . '/' . $savedName;
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

        // Check standard public path
        $publicFile = public_path($cleanPath);
        if (file_exists($publicFile) && is_file($publicFile)) {
            @unlink($publicFile);
            return true;
        }

        // Check storage symlink variants
        if (str_starts_with($cleanPath, 'storage/')) {
            $storageSubPath = substr($cleanPath, 8);
            $realStorage = storage_path('app/public/' . $storageSubPath);
            if (file_exists($realStorage) && is_file($realStorage)) {
                @unlink($realStorage);
                return true;
            }
        } else {
            $realStorage = storage_path('app/public/' . $cleanPath);
            if (file_exists($realStorage) && is_file($realStorage)) {
                @unlink($realStorage);
                return true;
            }
        }

        return false;
    }
}
