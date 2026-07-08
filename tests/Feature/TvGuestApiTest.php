<?php

namespace Tests\Feature;

use App\Models\ConnectedDevice;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\TvTemplate;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TvGuestApiTest extends TestCase
{
    use RefreshDatabase;

    private $plan;
    private $hotel;
    private $device;
    private $template;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plan = Plan::create([
            'name' => 'Pro Plan',
            'room_count' => 10,
            'price' => '2999.00',
            'status' => true,
            'description' => 'Test plan',
        ]);

        $this->hotel = HotelAdmin::create([
            'owner_name' => 'Test Owner',
            'email' => 'test@hotel.com',
            'password' => bcrypt('password123'),
            'phone' => '1234567890',
            'hotel_name' => 'Test Hotel',
            'hotel_location' => 'Test Location',
            'room_count' => 10,
            'plan_id' => $this->plan->id,
            'payment_status' => 'paid',
            'license_key' => 'TEST-LIC-KEY-1234',
            'approval_status' => 'approved',
            'status' => true,
        ]);

        $this->template = TvTemplate::create([
            'version' => '2.0',
            'file_path' => 'templates/template_v2_0.zip',
            'is_active' => true,
        ]);
    }

    public function test_api_returns_guest_info_null_when_no_guest_registered(): void
    {
        $loginPayload = [
            'license_key' => 'TEST-LIC-KEY-1234',
            'room_no' => '101',
            'deviceId' => 'device_123',
            'macAddress' => 'AA:BB:CC:DD:EE:FF',
        ];

        $response = $this->postJson('/api/tv/login', $loginPayload);

        $response->assertStatus(200)
            ->assertJsonPath('data.guest_info', null);
    }

    public function test_api_returns_guest_info_when_active_guest_exists(): void
    {
        $now = now();
        
        // Active Guest: Check-in was 1 hour ago, Check-out is in 2 hours
        $guest = Guest::create([
            'hotel_id' => $this->hotel->id,
            'name' => 'Guest User',
            'mobile_number' => '9876543210',
            'room_number' => '101',
            'check_in_datetime' => $now->copy()->subHour(),
            'check_out_datetime' => $now->copy()->addHours(2),
        ]);

        $loginPayload = [
            'license_key' => 'TEST-LIC-KEY-1234',
            'room_no' => '101',
            'deviceId' => 'device_123',
            'macAddress' => 'AA:BB:CC:DD:EE:FF',
        ];

        $response = $this->postJson('/api/tv/login', $loginPayload);

        $response->assertStatus(200)
            ->assertJsonPath('data.guest_info.name', 'Guest User')
            ->assertJsonPath('data.guest_info.mobile_number', '9876543210');

        $token = $response->json('data.auth.token');

        // Check Version API should also return the guest details
        $versionResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tv/template/check-version?version=1.0');

        $versionResponse->assertStatus(200)
            ->assertJsonPath('data.guest_info.name', 'Guest User')
            ->assertJsonPath('data.guest_info.mobile_number', '9876543210');
    }

    public function test_api_returns_guest_info_null_when_guest_not_yet_checked_in(): void
    {
        $now = now();
        
        // Scheduled Guest: Check-in is in 1 hour
        Guest::create([
            'hotel_id' => $this->hotel->id,
            'name' => 'Future Guest',
            'mobile_number' => '9876543210',
            'room_number' => '101',
            'check_in_datetime' => $now->copy()->addHour(),
            'check_out_datetime' => $now->copy()->addHours(5),
        ]);

        $loginPayload = [
            'license_key' => 'TEST-LIC-KEY-1234',
            'room_no' => '101',
            'deviceId' => 'device_123',
            'macAddress' => 'AA:BB:CC:DD:EE:FF',
        ];

        $response = $this->postJson('/api/tv/login', $loginPayload);

        $response->assertStatus(200)
            ->assertJsonPath('data.guest_info', null);
    }

    public function test_api_returns_guest_info_null_when_guest_already_checked_out(): void
    {
        $now = now();
        
        // Past Guest: Checked out 1 hour ago
        Guest::create([
            'hotel_id' => $this->hotel->id,
            'name' => 'Past Guest',
            'mobile_number' => '9876543210',
            'room_number' => '101',
            'check_in_datetime' => $now->copy()->subHours(5),
            'check_out_datetime' => $now->copy()->subHour(),
        ]);

        $loginPayload = [
            'license_key' => 'TEST-LIC-KEY-1234',
            'room_no' => '101',
            'deviceId' => 'device_123',
            'macAddress' => 'AA:BB:CC:DD:EE:FF',
        ];

        $response = $this->postJson('/api/tv/login', $loginPayload);

        $response->assertStatus(200)
            ->assertJsonPath('data.guest_info', null);
    }

    public function test_api_returns_guest_info_when_active_guest_has_open_checkout(): void
    {
        $now = now();
        
        // Open-ended Guest: Checked in 1 hour ago, checkout is null
        Guest::create([
            'hotel_id' => $this->hotel->id,
            'name' => 'Open Guest',
            'mobile_number' => '1122334455',
            'room_number' => '101',
            'check_in_datetime' => $now->copy()->subHour(),
            'check_out_datetime' => null,
        ]);

        $loginPayload = [
            'license_key' => 'TEST-LIC-KEY-1234',
            'room_no' => '101',
            'deviceId' => 'device_123',
            'macAddress' => 'AA:BB:CC:DD:EE:FF',
        ];

        $response = $this->postJson('/api/tv/login', $loginPayload);

        $response->assertStatus(200)
            ->assertJsonPath('data.guest_info.name', 'Open Guest')
            ->assertJsonPath('data.guest_info.check_out_datetime', null);
    }
}
