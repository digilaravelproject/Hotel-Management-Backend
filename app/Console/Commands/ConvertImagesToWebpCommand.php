<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HotelAdmin;
use App\Models\Amenity;
use App\Models\RoomInfo;
use App\Helpers\ImageHelper;

class ConvertImagesToWebpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-webp 
                            {--dry-run : Run in inspection mode without modifying database or files} 
                            {--force : Force execution without interactive confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all existing uploaded images across HotelAdmin, Amenities, and RoomInfo to high-definition WebP format and update database paths';

    /**
     * Statistics counters.
     */
    protected int $convertedCount = 0;
    protected int $skippedCount = 0;
    protected int $missingCount = 0;
    protected float $bytesSaved = 0;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info("==========================================================");
        $this->info("  WebP Bulk Image Optimizer & Database Migrator");
        $this->info("  Designed for 64-inch 4K TV Crispness & Ultra-fast Loading");
        $this->info("==========================================================");

        if ($isDryRun) {
            $this->warn(" Running in DRY-RUN mode. No files or database records will be changed.");
        } elseif (!$this->option('force')) {
            if (!$this->confirm('This will convert all existing non-WebP uploaded images to WebP and update database records. Proceed?', true)) {
                $this->info('Operation cancelled by user.');
                return 0;
            }
        }

        $this->newLine();

        // 1. Process HotelAdmin models
        $this->processHotelAdmins($isDryRun);

        // 2. Process Amenity models
        $this->processAmenities($isDryRun);

        // 3. Process RoomInfo models
        $this->processRoomInfos($isDryRun);

        $this->newLine();
        $this->info("==========================================================");
        $this->info("  Conversion Summary:");
        $this->line("  - Total Images Converted: <fg=green>{$this->convertedCount}</>");
        $this->line("  - Already WebP / Vector SVG (Skipped): <fg=cyan>{$this->skippedCount}</>");
        $this->line("  - Missing Files on Disk: <fg=yellow>{$this->missingCount}</>");
        if ($this->bytesSaved > 0) {
            $savedMb = round($this->bytesSaved / (1024 * 1024), 2);
            $this->line("  - Total Storage Saved: <fg=green>~{$savedMb} MB</>");
        }
        $this->info("==========================================================");

        return 0;
    }

    /**
     * Process HotelAdmin records.
     */
    protected function processHotelAdmins(bool $isDryRun): void
    {
        $hotels = HotelAdmin::all();
        $this->line("<fg=blue;options=bold>[1/3] Processing Hotel Admin Records (" . $hotels->count() . " found)...</>");

        foreach ($hotels as $hotel) {
            $modified = false;

            // 1. Hotel Logo
            if (!empty($hotel->hotel_logo)) {
                $newPath = $this->convertSinglePath(
                    $hotel->hotel_logo,
                    'uploads/hotel_logos',
                    500,
                    'logo',
                    1200,
                    $isDryRun,
                    "Hotel #{$hotel->id} Logo"
                );
                if ($newPath && $newPath !== $hotel->hotel_logo) {
                    $hotel->hotel_logo = $newPath;
                    $modified = true;
                }
            }

            // 2. Hotel Cover Image
            if (!empty($hotel->hotel_image)) {
                $newPath = $this->convertSinglePath(
                    $hotel->hotel_image,
                    'uploads/hotel_images',
                    1000,
                    'cover',
                    2560,
                    $isDryRun,
                    "Hotel #{$hotel->id} Cover"
                );
                if ($newPath && $newPath !== $hotel->hotel_image) {
                    $hotel->hotel_image = $newPath;
                    $modified = true;
                }
            }

            // 3. Slider Images (array)
            if (!empty($hotel->slider_images) && is_array($hotel->slider_images)) {
                $updatedSliders = [];
                $sliderChanged = false;

                foreach ($hotel->slider_images as $index => $sliderPath) {
                    $newPath = $this->convertSinglePath(
                        $sliderPath,
                        'uploads/hotel_sliders',
                        800,
                        'slider',
                        2560,
                        $isDryRun,
                        "Hotel #{$hotel->id} Slider [{$index}]"
                    );

                    if ($newPath) {
                        $updatedSliders[] = $newPath;
                        if ($newPath !== $sliderPath) {
                            $sliderChanged = true;
                        }
                    } else {
                        $updatedSliders[] = $sliderPath;
                    }
                }

                if ($sliderChanged) {
                    $hotel->slider_images = $updatedSliders;
                    $modified = true;
                }
            }

            // 4. Hotel Gallery Images (array)
            if (!empty($hotel->hotel_gallery_images) && is_array($hotel->hotel_gallery_images)) {
                $updatedGallery = [];
                $galleryChanged = false;

                foreach ($hotel->hotel_gallery_images as $index => $galleryPath) {
                    $newPath = $this->convertSinglePath(
                        $galleryPath,
                        'uploads/hotel_gallery',
                        800,
                        'gallery',
                        2560,
                        $isDryRun,
                        "Hotel #{$hotel->id} Gallery [{$index}]"
                    );

                    if ($newPath) {
                        $updatedGallery[] = $newPath;
                        if ($newPath !== $galleryPath) {
                            $galleryChanged = true;
                        }
                    } else {
                        $updatedGallery[] = $galleryPath;
                    }
                }

                if ($galleryChanged) {
                    $hotel->hotel_gallery_images = $updatedGallery;
                    $modified = true;
                }
            }

            if ($modified && !$isDryRun) {
                $hotel->save();
            }
        }
    }

    /**
     * Process Amenity records.
     */
    protected function processAmenities(bool $isDryRun): void
    {
        $amenities = Amenity::all();
        $this->line("<fg=blue;options=bold>[2/3] Processing Amenities (" . $amenities->count() . " found)...</>");

        foreach ($amenities as $amenity) {
            if (!empty($amenity->image)) {
                $newPath = $this->convertSinglePath(
                    $amenity->image,
                    'uploads/amenities',
                    800,
                    'amenity',
                    1920,
                    $isDryRun,
                    "Amenity #{$amenity->id} ({$amenity->name})"
                );

                if ($newPath && $newPath !== $amenity->image) {
                    $amenity->image = $newPath;
                    if (!$isDryRun) {
                        $amenity->save();
                    }
                }
            }
        }
    }

    /**
     * Process RoomInfo records.
     */
    protected function processRoomInfos(bool $isDryRun): void
    {
        $roomInfos = RoomInfo::all();
        $this->line("<fg=blue;options=bold>[3/3] Processing Room Infos (" . $roomInfos->count() . " found)...</>");

        foreach ($roomInfos as $roomInfo) {
            if (!empty($roomInfo->image)) {
                $newPath = $this->convertSinglePath(
                    $roomInfo->image,
                    'uploads/room_infos',
                    800,
                    'room_info',
                    1920,
                    $isDryRun,
                    "RoomInfo #{$roomInfo->id} ({$roomInfo->title})"
                );

                if ($newPath && $newPath !== $roomInfo->image) {
                    $roomInfo->image = $newPath;
                    if (!$isDryRun) {
                        $roomInfo->save();
                    }
                }
            }
        }
    }

    /**
     * Convert a single file path to WebP if not already converted.
     *
     * @param string $path Database relative path
     * @param string $destinationFolder Target folder (e.g. 'uploads/hotel_logos')
     * @param int $targetMaxKb Target max file size in KB
     * @param string $prefix File name prefix
     * @param int $maxDimension Max pixel dimension
     * @param bool $isDryRun Whether in dry-run mode
     * @param string $label Label for terminal logs
     * @return string|null New relative path or null if no change needed/failed
     */
    protected function convertSinglePath(
        string $path,
        string $destinationFolder,
        int $targetMaxKb,
        string $prefix,
        int $maxDimension,
        bool $isDryRun,
        string $label
    ): ?string {
        $cleanPath = trim(str_replace('\\', '/', $path), '/');
        $extension = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));

        // Skip if already .webp or .svg vector
        if ($extension === 'webp' || $extension === 'svg') {
            $this->skippedCount++;
            return $cleanPath;
        }

        // Resolve absolute physical path on disk
        $fullPath = $this->resolvePhysicalPath($cleanPath);

        if (!$fullPath || !file_exists($fullPath)) {
            $this->warn("  [!] {$label}: File not found on disk at '{$cleanPath}'");
            $this->missingCount++;
            return null;
        }

        $origSizeBytes = filesize($fullPath);
        $origSizeKb = round($origSizeBytes / 1024, 1);

        if ($isDryRun) {
            $this->line("  [DRY-RUN] {$label}: '{$cleanPath}' ({$origSizeKb} KB) -> Would convert to WebP");
            $this->convertedCount++;
            return $cleanPath;
        }

        // Perform conversion with ImageHelper
        try {
            $newRelativePath = ImageHelper::compressAndConvertToWebp(
                $fullPath,
                $destinationFolder,
                $targetMaxKb,
                $prefix,
                $maxDimension
            );

            $newFullPath = public_path($newRelativePath);
            $newSizeBytes = file_exists($newFullPath) ? filesize($newFullPath) : $origSizeBytes;
            $newSizeKb = round($newSizeBytes / 1024, 1);

            $savedBytes = max(0, $origSizeBytes - $newSizeBytes);
            $this->bytesSaved += $savedBytes;

            // Delete old file from disk (only if path changed)
            if ($fullPath !== $newFullPath && file_exists($fullPath)) {
                @unlink($fullPath);
            }

            $reductionPercent = $origSizeBytes > 0 ? round((($origSizeBytes - $newSizeBytes) / $origSizeBytes) * 100) : 0;
            $this->info("  [✓] {$label}: {$origSizeKb} KB -> {$newSizeKb} KB ({$reductionPercent}% reduced) -> {$newRelativePath}");
            $this->convertedCount++;

            return $newRelativePath;
        } catch (\Throwable $e) {
            $this->error("  [X] Failed to convert {$label}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Resolve the physical absolute path for a relative database path.
     */
    protected function resolvePhysicalPath(string $cleanPath): ?string
    {
        // 1. Direct public_path
        $p1 = public_path($cleanPath);
        if (file_exists($p1) && is_file($p1)) {
            return $p1;
        }

        // 2. Storage symlink path
        if (str_starts_with($cleanPath, 'storage/')) {
            $sub = substr($cleanPath, 8);
            $p2 = storage_path('app/public/' . $sub);
            if (file_exists($p2) && is_file($p2)) {
                return $p2;
            }
        }

        // 3. Storage app public direct path
        $p3 = storage_path('app/public/' . $cleanPath);
        if (file_exists($p3) && is_file($p3)) {
            return $p3;
        }

        return null;
    }
}
