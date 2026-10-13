<?php

namespace Tests\Feature;

use App\Models\ConnectedDevice;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\TvTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TvDemoSystemTest extends TestCase
{
    use RefreshDatabase;

    protected HotelAdmin $demoHotel;

    protected function setUp(): void
    {
        parent::setUp();

        $plan = Plan::create([
            'name' => 'Demo Enterprise Plan',
            'room_count' => 9999,
            'price' => '0.00',
            'status' => true,
        ]);

        $this->demoHotel = HotelAdmin::create([
            'owner_name' => 'Demo Showcase Manager',
            'email' => 'demo.showcase@digihotel.com',
            'password' => bcrypt('12345678'),
            'phone' => '+91 9999999999',
            'hotel_name' => 'DigiLounge Demo Grand Hotel',
            'hotel_location' => 'Cyber City, Delhi NCR',
            'city' => 'New Delhi',
            'room_count' => 9999,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'approval_status' => 'approved',
            'status' => true,
            'license_key' => 'DEMO-MASTER-SHOWCASE',
            'description' => 'Official Demo Hotel',
        ]);

        TvTemplate::create([
            'version' => '1.0',
            'theme_id' => 1,
            'file_path' => 'templates/theme1.zip',
            'is_active' => true,
        ]);
    }

    public function test_tv_can_register_for_demo_without_license_key(): void
    {
        $response = $this->postJson('/api/tv/demo-login', [
            'deviceId' => 'TV-DEVICE-ALPHA-01',
            'macAddress' => '11:22:33:44:55:66',
            'model' => 'Sony Bravia 4K',
            'brand' => 'Sony',
            'osVersion' => 'Android 12',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status', true);
        $response->assertJsonPath('data.auth.is_demo', true);
        $response->assertJsonPath('data.auth.demo_days_left', 7);
        $response->assertJsonPath('data.hotel.hotel_name', 'DigiLounge Demo Grand Hotel');

        $this->assertDatabaseHas('connected_devices', [
            'device_id' => 'TV-DEVICE-ALPHA-01',
            'is_demo' => true,
            'demo_status' => 'active',
        ]);
    }

    public function test_existing_active_demo_tv_can_refresh_login(): void
    {
        // First login
        $this->postJson('/api/tv/demo-login', [
            'deviceId' => 'TV-DEVICE-ALPHA-02',
            'macAddress' => '22:33:44:55:66:77',
        ]);

        // Second login (reboot / app reopen)
        $response = $this->postJson('/api/tv/demo-login', [
            'deviceId' => 'TV-DEVICE-ALPHA-02',
            'macAddress' => '22:33:44:55:66:77',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('status', true);
        $response->assertJsonPath('data.auth.is_demo', true);
        $this->assertEquals(1, ConnectedDevice::where('device_id', 'TV-DEVICE-ALPHA-02')->count());
    }

    public function test_expired_demo_tv_is_blocked_from_logging_in_again(): void
    {
        // Register demo TV and artificially expire it
        $this->postJson('/api/tv/demo-login', [
            'deviceId' => 'TV-DEVICE-EXPIRED-01',
            'macAddress' => 'AA:BB:CC:DD:EE:01',
        ]);

        $device = ConnectedDevice::where('device_id', 'TV-DEVICE-EXPIRED-01')->firstOrFail();
        $device->update([
            'demo_expires_at' => now()->subDay(),
            'demo_status' => 'expired',
        ]);

        // Attempt demo login again
        $response = $this->postJson('/api/tv/demo-login', [
            'deviceId' => 'TV-DEVICE-EXPIRED-01',
            'macAddress' => 'AA:BB:CC:DD:EE:01',
        ]);

        $response->assertStatus(403);
        $response->assertJsonPath('status', false);
        $response->assertJsonPath('error_code', 'DEMO_EXPIRED');
    }

    public function test_expired_demo_tv_is_locked_out_of_protected_endpoints_while_active_demo_continues(): void
    {
        // TV 1: Expired Demo
        $deviceExpired = ConnectedDevice::create([
            'hotel_admin_id' => $this->demoHotel->id,
            'room_no' => 'DEMO-101',
            'device_id' => 'TV-EXP-101',
            'mac_address' => 'AA:11:22:33:44:55',
            'api_token' => 'token-expired-tv',
            'is_demo' => true,
            'demo_expires_at' => now()->subHours(2),
            'demo_status' => 'active',
        ]);

        // TV 2: Active Demo (Still has 5 days)
        $deviceActive = ConnectedDevice::create([
            'hotel_admin_id' => $this->demoHotel->id,
            'room_no' => 'DEMO-102',
            'device_id' => 'TV-ACT-102',
            'mac_address' => 'BB:11:22:33:44:55',
            'api_token' => 'token-active-tv',
            'is_demo' => true,
            'demo_expires_at' => now()->addDays(5),
            'demo_status' => 'active',
        ]);

        // Request from TV 1 (Expired)
        $response1 = $this->withToken('token-expired-tv')->getJson('/api/tv/template/check-version');
        $response1->assertStatus(403);
        $response1->assertJsonPath('error_code', 'DEMO_EXPIRED');

        // Request from TV 2 (Active) should succeed completely
        $response2 = $this->withToken('token-active-tv')->getJson('/api/tv/template/check-version');
        $response2->assertStatus(200);
        $response2->assertJsonPath('status', true);
    }

    public function test_demo_tv_can_convert_to_paid_hotel_with_real_license_key(): void
    {
        // 1. Initial demo registration
        $this->postJson('/api/tv/demo-login', [
            'deviceId' => 'TV-CONVERT-01',
            'macAddress' => '99:88:77:66:55:44',
        ]);

        $device = ConnectedDevice::where('device_id', 'TV-CONVERT-01')->firstOrFail();
        $this->assertTrue((bool) $device->is_demo);

        // 2. Paid hotel creates license
        $paidHotel = HotelAdmin::create([
            'owner_name' => 'Paid Hotel Owner',
            'email' => 'realhotel@test.com',
            'password' => bcrypt('password'),
            'phone' => '8888888888',
            'hotel_name' => 'The Oberoi Luxury Palace',
            'hotel_location' => 'Mumbai',
            'room_count' => 50,
            'plan_id' => $this->demoHotel->plan_id,
            'payment_status' => 'paid',
            'approval_status' => 'approved',
            'status' => true,
            'license_key' => 'PAID-LIC-8899-7711',
        ]);

        // 3. TV activates paid license key via standard login
        $response = $this->postJson('/api/tv/login', [
            'license_key' => 'PAID-LIC-8899-7711',
            'room_no' => 'Room 201',
            'deviceId' => 'TV-CONVERT-01',
            'macAddress' => '99:88:77:66:55:44',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.auth.is_demo', false);
        $response->assertJsonPath('data.hotel.hotel_name', 'The Oberoi Luxury Palace');

        $device->refresh();
        $this->assertFalse((bool) $device->is_demo);
        $this->assertEquals('converted', $device->demo_status);
        $this->assertEquals($paidHotel->id, $device->hotel_admin_id);
    }
}
