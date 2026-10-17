<?php

namespace Tests\Feature;

use App\Models\ConnectedDevice;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\TvTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TvApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_tv_login_and_version_check_apis(): void
    {
        // 1. Create a Subscription Plan
        $plan = Plan::create([
            'name' => 'Pro Plan',
            'room_count' => 10,
            'price' => '2999.00',
            'status' => true,
            'description' => 'Test plan',
        ]);

        // 2. Create a Hotel Admin
        $hotel = HotelAdmin::create([
            'owner_name' => 'Test Owner',
            'email' => 'test@hotel.com',
            'password' => bcrypt('password123'),
            'phone' => '1234567890',
            'hotel_name' => 'Test Hotel',
            'hotel_location' => 'Test Location',
            'room_count' => 10,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'license_key' => 'TEST-LIC-KEY-1234',
            'approval_status' => 'approved',
            'status' => true,
        ]);

        // 3. Create an active TvTemplate
        TvTemplate::create([
            'version' => '2.0',
            'file_path' => 'templates/template_v2_0.zip',
            'is_active' => true,
        ]);

        // Mock a logo so we can test base64 conversion
        $logoDir = public_path('uploads/hotel_logos');
        if (!file_exists($logoDir)) {
            mkdir($logoDir, 0755, true);
        }
        $logoPath = 'uploads/hotel_logos/test_logo.png';
        // 1x1 transparent PNG hex
        $pngBytes = hex2bin('89504E470D0A1A0A0000000D49484452000000010000000108060000001F15C4890000000D4944415478DA6360180500000A00015708021A0000000049454E44AE426082');
        file_put_contents(public_path($logoPath), $pngBytes);

        $hotel->update([
            'hotel_logo' => $logoPath,
        ]);

        // 4. Test Login API
        $loginPayload = [
            'license_key' => 'TEST-LIC-KEY-1234',
            'room_no' => '101',
            'deviceId' => 'device_123',
            'macAddress' => 'AA:BB:CC:DD:EE:FF',
            'ipAddress' => '192.168.1.1',
        ];

        $response = $this->postJson('/api/tv/login', $loginPayload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'auth' => ['token'],
                    'template' => ['latest_version', 'old_version', 'download_url', 'uploaded_at', 'is_update_available'],
                    'device' => ['room_no', 'device_id', 'mac_address'],
                    'hotel' => [
                        'hotel_name',
                        'media' => ['logo_image', 'cover_image', 'slider_images'],
                        'active_plan' => ['plan_name', 'plan_price', 'purchase_date', 'expiry_date'],
                    ],
                    'active_ott',
                    'menus',
                ]
            ]);

        $response->assertJsonPath('message', 'TV logged in successfully.');
        $token = $response->json('data.auth.token');
        $this->assertNotEmpty($token);

        // Assert asset URL format works
        $logoUrl = $response->json('data.hotel.media.logo_image');
        $this->assertStringContainsString('uploads/hotel_logos/test_logo.png', $logoUrl);

        // Assert plan details
        $response->assertJsonPath('data.hotel.active_plan.plan_name', 'Pro Plan');
        $response->assertJsonPath('data.hotel.active_plan.plan_price', '2999.00');
        $response->assertJsonPath('data.template.latest_version', '2.0');
        $response->assertJsonPath('data.template.is_update_available', false);

        // 5. Test Version Check API - Unauthenticated
        $this->getJson('/api/tv/template/check-version')
            ->assertStatus(401);

        // 6. Test Version Check API - Authenticated (Update available: version=1.0)
        $versionResponse1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tv/template/check-version?version=1.0');

        $versionResponse1->assertStatus(200);
        $versionResponse1->assertJsonPath('message', 'Template version details fetched successfully.');
        $versionResponse1->assertJsonPath('data.template.is_update_available', true);
        $versionResponse1->assertJsonPath('data.template.latest_version', '2.0');

        // 7. Test Version Check API - Authenticated (Up to date: version=2.0)
        $versionResponse2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tv/template/check-version?version=2.0');

        $versionResponse2->assertStatus(200);
        $versionResponse2->assertJsonPath('message', 'Template version details fetched successfully.');
        $versionResponse2->assertJsonPath('data.template.is_update_available', false);
        // 8. Test Hotel-Isolated Cache Invalidation
        // Update Hotel Name for $hotel
        $hotel->update(['hotel_name' => 'Updated Hotel Name']);

        // Check Version API should return updated hotel name (cache was cleared by HotelAdminObserver)
        $versionResponse3 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tv/template/check-version?version=2.0');

        $versionResponse3->assertStatus(200);
        $versionResponse3->assertJsonPath('data.hotel.hotel_name', 'Updated Hotel Name');

        // 9. Test RoomInfo Cache Invalidation and Specifications Serialization
        $roomInfo = \App\Models\RoomInfo::create([
            'hotel_admin_id' => $hotel->id,
            'sr_no' => 1,
            'title' => 'Deluxe King',
            'description' => 'King bed room',
            'specifications' => ['Wi-Fi', 'Balcony'],
            'status' => true,
        ]);

        $versionResponse4 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tv/template/check-version?version=2.0');

        $versionResponse4->assertStatus(200);
        $versionResponse4->assertJsonPath('data.room_info.0.title', 'Deluxe King');
        $versionResponse4->assertJsonPath('data.room_info.0.specifications', ['Wi-Fi', 'Balcony']);

        // Update specifications
        $roomInfo->update([
            'specifications' => ['4K TV', 'Jacuzzi', 'Ocean View'],
        ]);

        $versionResponse5 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tv/template/check-version?version=2.0');

        $versionResponse5->assertStatus(200);
        $versionResponse5->assertJsonPath('data.room_info.0.specifications', ['4K TV', 'Jacuzzi', 'Ocean View']);

        // Clean up dummy file
        @unlink(public_path($logoPath));
    }

    public function test_server_timestamp_and_timezone_are_present_in_tv_endpoints()
    {
        $plan = Plan::create([
            'name' => 'Time Sync Plan',
            'room_count' => 10,
            'price' => '1999.00',
            'status' => true,
        ]);

        $hotel = HotelAdmin::create([
            'owner_name' => 'Time Test Owner',
            'email' => 'time_test@hotel.com',
            'password' => bcrypt('password123'),
            'phone' => '9876543210',
            'license_key' => 'TIME-TEST-KEY-001',
            'hotel_name' => 'Time Test Grand',
            'hotel_location' => 'Connaught Place, New Delhi',
            'city' => 'New Delhi',
            'room_count' => 10,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'approval_status' => 'approved',
            'status' => true,
        ]);

        $loginPayload = [
            'license_key' => 'TIME-TEST-KEY-001',
            'room_no' => '101',
            'deviceId' => 'TIME_DEVICE_001',
            'macAddress' => 'AA:11:22:33:44:55',
        ];

        // 1. Test POST /api/tv/login
        $loginRes = $this->postJson('/api/tv/login', $loginPayload);
        $loginRes->assertStatus(200);
        $loginRes->assertJsonStructure([
            'status',
            'message',
            'server_time',
            'timestamp',
            'timezone',
            'utc_offset_seconds',
            'data' => [
                'hotel' => ['timezone', 'city', 'hotel_location'],
            ]
        ]);
        $this->assertEquals('Asia/Kolkata', $loginRes->json('timezone'));
        $this->assertEquals('Asia/Kolkata', $loginRes->json('data.hotel.timezone'));
        $this->assertEquals('New Delhi', $loginRes->json('data.hotel.city'));
        $this->assertIsInt($loginRes->json('timestamp'));
        $this->assertEquals(19800, $loginRes->json('utc_offset_seconds'));
        $token = $loginRes->json('data.auth.token');

        // 2. Test GET /api/tv/template/check-version
        $checkRes = $this->withToken($token)->getJson('/api/tv/template/check-version');
        $checkRes->assertStatus(200);
        $this->assertEquals('Asia/Kolkata', $checkRes->json('timezone'));
        $this->assertEquals('Asia/Kolkata', $checkRes->json('data.hotel.timezone'));
        $this->assertNotNull($checkRes->json('server_time'));
        $this->assertIsInt($checkRes->json('timestamp'));

        // 3. Test alias GET /api/tv/check-template-version
        $aliasRes = $this->withToken($token)->getJson('/api/tv/check-template-version');
        $aliasRes->assertStatus(200);
        $this->assertEquals('Asia/Kolkata', $aliasRes->json('timezone'));
        $this->assertEquals('Asia/Kolkata', $aliasRes->json('data.hotel.timezone'));
        $this->assertNotNull($aliasRes->json('server_time'));

        // 4. Test POST /api/tv/heartbeat
        $heartbeatRes = $this->withToken($token)->postJson('/api/tv/heartbeat');
        $heartbeatRes->assertStatus(200);
        $this->assertEquals('Asia/Kolkata', $heartbeatRes->json('timezone'));
        $this->assertEquals('Asia/Kolkata', $heartbeatRes->json('data.hotel.timezone'));
        $this->assertNotNull($heartbeatRes->json('server_time'));

        // 5. Test POST /api/tv/demo-login
        HotelAdmin::firstOrCreate(
            ['license_key' => 'DEMO-MASTER-SHOWCASE'],
            [
                'owner_name' => 'Demo Manager',
                'email' => 'demo_time@digihotel.com',
                'password' => bcrypt('password123'),
                'phone' => '9999999999',
                'hotel_name' => 'DigiLounge Demo Hotel',
                'hotel_location' => 'Cyber City, Delhi NCR',
                'city' => 'Delhi NCR',
                'room_count' => 999,
                'plan_id' => $plan->id,
                'payment_status' => 'paid',
                'approval_status' => 'approved',
                'status' => true,
            ]
        );

        $demoRes = $this->postJson('/api/tv/demo-login', [
            'deviceId' => 'TIME_DEMO_DEVICE_002',
            'macAddress' => 'BB:11:22:33:44:66',
        ]);
        $demoRes->assertStatus(200);
        $this->assertEquals('Asia/Kolkata', $demoRes->json('timezone'));
        $this->assertEquals('Asia/Kolkata', $demoRes->json('data.hotel.timezone'));
        $this->assertNotNull($demoRes->json('server_time'));
        $this->assertIsInt($demoRes->json('timestamp'));
    }

    public function test_unpaid_hotel_license_key_cannot_login_on_tv(): void
    {
        $plan = Plan::create([
            'name' => 'Standard Plan',
            'room_count' => 15,
            'price' => '1999.00',
            'status' => true,
        ]);

        $unpaidHotel = HotelAdmin::create([
            'owner_name' => 'Unpaid Owner',
            'email' => 'unpaid@hotel.com',
            'password' => bcrypt('password123'),
            'phone' => '9888877777',
            'hotel_name' => 'Unpaid Hotel',
            'hotel_location' => 'Mumbai',
            'room_count' => 15,
            'plan_id' => $plan->id,
            'payment_status' => 'unpaid',
            'license_key' => 'UNPAID-KEY-9999',
            'approval_status' => 'approved',
            'status' => true,
        ]);

        $response = $this->postJson('/api/tv/login', [
            'license_key' => 'UNPAID-KEY-9999',
            'room_no' => '101',
            'deviceId' => 'DEV_UNPAID_TV_001',
            'macAddress' => 'AA:BB:CC:DD:EE:FF',
        ]);

        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => 'Subscription payment is pending. Please complete your subscription payment to activate TV devices.',
        ]);
    }
}
