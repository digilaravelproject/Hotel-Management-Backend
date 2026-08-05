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

        // Clean up dummy file
        @unlink(public_path($logoPath));
    }
}
