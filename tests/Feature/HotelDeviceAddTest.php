<?php

namespace Tests\Feature;

use App\Models\ConnectedDevice;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\TvPairSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelDeviceAddTest extends TestCase
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
            'owner_name' => 'Rajesh Sharma',
            'email' => 'rajesh@hoteltest.com',
            'password' => bcrypt('password123'),
            'phone' => '+91 99999 88888',
            'hotel_name' => 'Sunset Resort',
            'hotel_location' => 'Calangute, Goa',
            'room_count' => 10,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'license_key' => 'TEST-HOTEL-KEY-001',
            'approval_status' => 'approved',
            'status' => true,
        ]);
    }

    public function test_hotel_admin_can_quick_add_device(): void
    {
        $response = $this->actingAs($this->hotel, 'hotel_admin')
                         ->postJson(route('hotel.devices.store'), [
                             'room_no' => '101',
                             'brand' => 'Samsung',
                             'model' => 'Crystal 4K',
                         ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Room 101 TV device connected and added successfully!',
                 ]);

        $this->assertDatabaseHas('connected_devices', [
            'hotel_admin_id' => $this->hotel->id,
            'room_no' => '101',
            'brand' => 'Samsung',
        ]);
    }

    public function test_quick_add_device_respects_license_limit(): void
    {
        // Hotel has room_count = 10 (allowed_device_limit = 10)
        // Fill up to 10
        for ($i = 1; $i <= 10; $i++) {
            ConnectedDevice::create([
                'hotel_admin_id' => $this->hotel->id,
                'room_no' => (string) (100 + $i),
                'device_id' => 'DEV-' . $i,
                'mac_address' => "00:11:22:33:44:0{$i}",
                'brand' => 'Android TV',
            ]);
        }

        // 11th should be blocked with 403
        $response = $this->actingAs($this->hotel, 'hotel_admin')
                         ->postJson(route('hotel.devices.store'), [
                             'room_no' => '111',
                             'brand' => 'Sony',
                         ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_pair_device_with_flexible_code_format(): void
    {
        $session = TvPairSession::create([
            'pair_code' => '8F2A-9K3P',
            'device_id' => 'TV-PAIR-TEST-1',
            'mac_address' => 'AA:BB:CC:DD:EE:FF',
            'ip_address' => '192.168.1.50',
            'brand' => 'Mi TV',
            'model' => '4A Pro',
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
        ]);

        // Submit without hyphen
        $response = $this->actingAs($this->hotel, 'hotel_admin')
                         ->postJson(route('hotel.devices.pair'), [
                             'pair_code' => '8f2a9k3p', // lowercase and without hyphen
                             'room_no' => '205',
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('connected_devices', [
            'hotel_admin_id' => $this->hotel->id,
            'room_no' => '205',
            'device_id' => 'TV-PAIR-TEST-1',
        ]);
    }
}
