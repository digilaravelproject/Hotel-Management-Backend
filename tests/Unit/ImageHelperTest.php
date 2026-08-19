<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use Illuminate\Http\UploadedFile;

class ImageHelperTest extends TestCase
{
    public function test_converts_uploaded_image_to_webp_and_compresses_file(): void
    {
        // Create dummy PNG uploaded file
        $file = UploadedFile::fake()->image('hotel_banner.png', 1920, 1080);

        $savedPath = ImageHelper::compressAndConvertToWebp($file, 'uploads/test_images', 500, 'test_cover', 2560);

        $this->assertStringEndsWith('.webp', $savedPath);
        $this->assertFileExists(public_path($savedPath));

        $fileSizeKb = filesize(public_path($savedPath)) / 1024;
        $this->assertLessThanOrEqual(500, $fileSizeKb);

        // Clean up
        $deleted = ImageHelper::deleteFile($savedPath);
        $this->assertTrue($deleted);
        $this->assertFileDoesNotExist(public_path($savedPath));
    }

    public function test_preserves_svg_vector_files(): void
    {
        $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="red" /></svg>';
        $file = UploadedFile::fake()->createWithContent('icon.svg', $svgContent);

        $savedPath = ImageHelper::compressAndConvertToWebp($file, 'uploads/test_images', 500, 'icon', 2560);

        $this->assertStringEndsWith('.svg', $savedPath);
        $this->assertFileExists(public_path($savedPath));

        // Clean up
        $deleted = ImageHelper::deleteFile($savedPath);
        $this->assertTrue($deleted);
    }
}
