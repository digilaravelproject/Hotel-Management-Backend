<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\HotelAdmin;
use App\Models\Amenity;
use App\Models\RoomInfo;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConvertImagesToWebpCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_artisan_command_converts_existing_images_to_webp(): void
    {
        $plan = Plan::create([
            'name' => 'Basic',
            'room_count' => 5,
            'price' => '999.00',
            'status' => true,
        ]);

        // Create dummy PNG files
        $logoDir = public_path('uploads/hotel_logos');
        if (!file_exists($logoDir)) {
            mkdir($logoDir, 0755, true);
        }

        $oldLogoPath = 'uploads/hotel_logos/sample_old_logo_' . time() . '.png';
        $fullPath = public_path($oldLogoPath);
        $gdImg = imagecreatetruecolor(120, 120);
        $bg = imagecolorallocate($gdImg, 100, 150, 200);
        imagefilledrectangle($gdImg, 0, 0, 120, 120, $bg);
        imagepng($gdImg, $fullPath);
        imagedestroy($gdImg);

        $hotel = HotelAdmin::create([
            'owner_name' => 'Owner Test',
            'email' => 'owner@example.com',
            'password' => bcrypt('secret123'),
            'phone' => '9999999999',
            'hotel_name' => 'Grand Palace',
            'hotel_location' => 'Goa',
            'room_count' => 5,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'hotel_logo' => $oldLogoPath,
            'status' => true,
        ]);

        $this->artisan('images:convert-to-webp', ['--force' => true])
            ->assertExitCode(0);

        $hotel->refresh();

        // Hotel logo should now end with .webp
        $this->assertStringEndsWith('.webp', $hotel->hotel_logo);
        $this->assertFileExists(public_path($hotel->hotel_logo));
        $this->assertFileDoesNotExist(public_path($oldLogoPath));

        // Clean up test webp
        if (file_exists(public_path($hotel->hotel_logo))) {
            @unlink(public_path($hotel->hotel_logo));
        }
    }
}
