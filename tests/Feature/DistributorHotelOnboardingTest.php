<?php

namespace Tests\Feature;

use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DistributorHotelOnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'distributor']);
    }

    public function test_distributor_can_view_hotel_create_page(): void
    {
        $distributor = User::factory()->create();
        $distributor->assignRole('distributor');

        $response = $this->actingAs($distributor)
                         ->get(route('distributor.hotels.create'));

        $response->assertStatus(200);
        $response->assertSee('Onboard New Partner Hotel');
        // Ensure "Number of Rooms" field is NOT in the form
        $response->assertDontSee('Number of Rooms / Smart TVs');
    }

    public function test_distributor_can_onboard_hotel_without_specifying_room_count(): void
    {
        $distributor = User::factory()->create();
        $distributor->assignRole('distributor');

        $plan = Plan::create([
            'name' => 'Gold Suite Package',
            'room_count' => 50,
            'price' => '4999.00',
            'status' => true,
        ]);

        $payload = [
            'hotel_name' => 'Seaside Villa & Resort',
            'owner_name' => 'Vikram Sharma',
            'email' => 'admin@seasidevilla.test',
            'password' => 'secret1234',
            'phone' => '+91 98989 89898',
            'hotel_location' => 'Baga Beach Front',
            'city' => 'Goa',
            'plan_id' => $plan->id,
            // room_count is intentionally omitted
        ];

        $response = $this->actingAs($distributor)
                         ->post(route('distributor.hotels.store'), $payload);

        $response->assertRedirect(route('distributor.hotels.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hotel_admins', [
            'hotel_name' => 'Seaside Villa & Resort',
            'email' => 'admin@seasidevilla.test',
            'distributor_id' => $distributor->id,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'room_count' => 50, // resolved from plan
        ]);

        $createdHotel = HotelAdmin::where('email', 'admin@seasidevilla.test')->first();
        $this->assertNotNull($createdHotel->license_key);
        $this->assertStringStartsWith('DIST-', $createdHotel->license_key);
    }
}
