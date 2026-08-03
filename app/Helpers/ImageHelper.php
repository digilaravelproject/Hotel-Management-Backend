<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Compress an uploaded image file, convert it to high-quality WebP format (target <= 1MB),
     * and save it to the specified public storage directory.
     *
     * @param UploadedFile $file The uploaded image file (supports JPEG, PNG, WEBP, SVG, etc.)
     * @param string $folder Relative path inside storage/app/public (e.g. 'uploads/amenities')
     * @param int $targetMaxKb Target max size in KB (default 1000 KB = ~1MB)
     * @return string Relative public asset path (e.g. 'storage/uploads/amenities/filename.webp')
     */
    public static function compressAndConvertToWebp(UploadedFile $file, string $folder = 'uploads/amenities', int $targetMaxKb = 1000): string
    {
        $destinationPath = storage_path('app/public/' . trim($folder, '/'));

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = 'amenity_' . time() . '_' . Str::random(8);

        // SVG files are vector graphics - store directly without raster conversion
        if ($extension === 'svg' || $file->getClientMimeType() === 'image/svg+xml') {
            $savedName = $filename . '.svg';
            $file->move($destinationPath, $savedName);
            return 'storage/' . trim($folder, '/') . '/' . $savedName;
        }

        // Read raw file content into GD image resource
        $fileContent = file_get_contents($file->getRealPath());
        $sourceImage = @imagecreatefromstring($fileContent);

        if (!$sourceImage) {
            // Fallback to standard Laravel storage if GD cannot parse the image
            $path = $file->store($folder, 'public');
            return 'storage/' . $path;
        }

        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);

        // Create a truecolor image buffer preserving transparency for WebP
        $canvas = imagecreatetruecolor($width, $height);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        // Copy source image to canvas
        imagecopyresampled($canvas, $sourceImage, 0, 0, 0, 0, $width, $height, $width, $height);

        $savedName = $filename . '.webp';
        $fullPath = $destinationPath . '/' . $savedName;

        // Start with high quality (90) for TV displays, iteratively compress if size > targetMaxKb
        $quality = 90;
        do {
            ob_start();
            imagewebp($canvas, null, $quality);
            $imageData = ob_get_clean();
            $fileSizeKb = strlen($imageData) / 1024;

            if ($fileSizeKb <= $targetMaxKb || $quality <= 30) {
                file_put_contents($fullPath, $imageData);
                break;
            }

            // Reduce quality slightly for next iteration
            $quality -= 8;
        } while ($quality >= 25);

        // Free GD memory
        imagedestroy($sourceImage);
        imagedestroy($canvas);

        return 'storage/' . trim($folder, '/') . '/' . $savedName;
    }
}
