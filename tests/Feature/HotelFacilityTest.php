<?php

namespace Tests\Feature;

use App\Models\HotelAdmin;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HotelFacilityTest extends TestCase
{
    use RefreshDatabase;

    protected HotelAdmin $hotel;

    protected function setUp(): void
    {
        parent::setUp();

        $plan = Plan::create([
            'name' => 'Premium TV Plan',
            'room_count' => 10,
            'price' => '1999.00',
            'status' => true,
        ]);

        $this->hotel = HotelAdmin::create([
            'owner_name' => 'Facility Tester',
            'email' => 'facility@hoteltest.com',
            'password' => bcrypt('password123'),
            'phone' => '+91 99999 77777',
            'hotel_name' => 'Grand Palace',
            'hotel_location' => 'Mumbai',
            'room_count' => 10,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'license_key' => 'FACILITY-KEY-001',
            'approval_status' => 'approved',
            'status' => true,
            // Simulating legacy/unstandardized gallery items like on production
            'hotel_gallery_images' => [
                'uploads/hotel_gallery/facility_1.webp',
                [
                    'title' => 'Swimming Pool #2',
                    'image' => 'uploads/hotel_gallery/facility_2.webp',
                    // Note: id is intentionally missing to reproduce user's bug
                ],
            ],
        ]);
    }

    public function test_index_normalizes_and_persists_legacy_facility_ids(): void
    {
        $response = $this->actingAs($this->hotel, 'hotel_admin')
                         ->get(route('hotel.facilities.index'));

        $response->assertStatus(200);

        $this->hotel->refresh();
        $gallery = $this->hotel->hotel_gallery_images;

        $this->assertCount(2, $gallery);
        $this->assertNotEmpty($gallery[0]['id']);
        $this->assertNotEmpty($gallery[1]['id']);
    }

    public function test_update_facility_by_id_or_one_based_index(): void
    {
        // 1. Visit index to normalize
        $this->actingAs($this->hotel, 'hotel_admin')
             ->get(route('hotel.facilities.index'));

        $this->hotel->refresh();
        $item2 = $this->hotel->hotel_gallery_images[1];

        // 2. Update item 2 using its generated ID
        $response = $this->actingAs($this->hotel, 'hotel_admin')
                         ->put(route('hotel.facilities.update', $item2['id']), [
                             'title' => 'Updated Infinity Pool',
                             'description' => 'Beautiful heated pool with ocean view.',
                             'features' => ['Heated', 'Ocean View'],
                         ]);

        $response->assertRedirect(route('hotel.facilities.index'))
                 ->assertSessionHas('success');

        $this->hotel->refresh();
        $updatedItem = $this->hotel->hotel_gallery_images[1];
        $this->assertEquals('Updated Infinity Pool', $updatedItem['title']);
        $this->assertEquals('Beautiful heated pool with ocean view.', $updatedItem['description']);
    }

    public function test_update_facility_by_legacy_number_index_2(): void
    {
        // Even if requested with '2' directly, it must find and update
        $response = $this->actingAs($this->hotel, 'hotel_admin')
                         ->put(route('hotel.facilities.update', '2'), [
                             'title' => 'Rooftop Lounge 2',
                             'description' => 'Sunset cocktails and dining.',
                             'features' => ['Cocktails', 'Music'],
                         ]);

        $response->assertRedirect(route('hotel.facilities.index'))
                 ->assertSessionHas('success');

        $this->hotel->refresh();
        $updatedItem = $this->hotel->hotel_gallery_images[1];
        $this->assertEquals('Rooftop Lounge 2', $updatedItem['title']);
    }
}
