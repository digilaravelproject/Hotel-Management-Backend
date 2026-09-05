<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\ConnectedDevice;
use App\Models\TvTemplate;
use Illuminate\Support\Str;

class TvMultiThemeTest extends TestCase
{
    use RefreshDatabase;

    protected HotelAdmin $hotel;
    protected ConnectedDevice $device;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create a Plan
        $plan = Plan::create([
            'name' => 'Standard Plan',
            'room_count' => 20,
            'price' => 1999.00,
            'status' => true,
        ]);

        // 2. Create a Hotel Admin
        $this->hotel = HotelAdmin::create([
            'owner_name' => 'John Doe',
            'email' => 'hotel@test.com',
            'password' => bcrypt('secret123'),
            'phone' => '9876543210',
            'hotel_name' => 'Grand Palace',
            'hotel_location' => 'Mumbai',
            'room_count' => 20,
            'plan_id' => $plan->id,
            'selected_theme_id' => 1,
            'payment_status' => 'paid',
            'license_key' => 'PALACE-' . Str::random(8),
            'approval_status' => 'approved',
            'status' => true,
        ]);

        // 3. Create Connected TV Device
        $this->device = ConnectedDevice::create([
            'hotel_admin_id' => $this->hotel->id,
            'room_no' => '101',
            'device_id' => 'DEV-' . Str::random(10),
            'mac_address' => 'AA:BB:CC:DD:EE:01',
            'ip_address' => '192.168.1.101',
            'model' => 'Android TV Box',
            'brand' => 'Xiaomi',
            'os_version' => '11.0',
            'status' => true,
            'api_token' => Str::random(60),
        ]);

        // 4. Create Theme 1 Build
        TvTemplate::create([
            'theme_id' => 1,
            'theme_name' => 'Classic Luxury',
            'version' => '1.0',
            'file_path' => 'templates/theme_1_v1_0.zip',
            'is_active' => true,
        ]);

        // 5. Create Theme 2 Build
        TvTemplate::create([
            'theme_id' => 2,
            'theme_name' => 'Modern Minimalist',
            'version' => '2.0',
            'file_path' => 'templates/theme_2_v2_0.zip',
            'is_active' => true,
        ]);
    }

    public function test_tv_receives_default_theme_1()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->device->api_token,
            'Accept' => 'application/json',
        ])->getJson('/api/tv/template/check-version');

        $response->assertStatus(200);
        $response->assertJsonPath('data.template.template_id', 1);
        $response->assertJsonPath('data.template.latest_version', '1.0');
    }

    public function test_hotel_switches_to_theme_2_and_tv_receives_theme_2()
    {
        // Hotel selects Theme 2
        $this->actingAs($this->hotel, 'hotel_admin')
            ->post('/hotel/themes/select', [
                'theme_id' => 2,
            ])
            ->assertRedirect();

        $this->hotel->refresh();
        $this->assertEquals(2, $this->hotel->selected_theme_id);

        // TV calls check-version with its current theme_id=1
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->device->api_token,
            'Accept' => 'application/json',
        ])->getJson('/api/tv/template/check-version?theme_id=1&version=1.0');

        $response->assertStatus(200);
        // Should indicate update is available because theme changed to 2
        $response->assertJsonPath('data.template.is_update_available', true);
        $response->assertJsonPath('data.template.template_id', 2);
        $response->assertJsonPath('data.template.latest_version', '2.0');
    }

    public function test_tv_already_on_theme_2_checks_version()
    {
        // Switch hotel to Theme 2
        $this->hotel->update(['selected_theme_id' => 2]);

        // TV already downloaded Theme 2 v2.0
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->device->api_token,
            'Accept' => 'application/json',
        ])->getJson('/api/tv/template/check-version?theme_id=2&version=2.0');

        $response->assertStatus(200);
        $response->assertJsonPath('data.template.is_update_available', false);
        $response->assertJsonPath('data.template.template_id', 2);
        $response->assertJsonPath('data.template.latest_version', '2.0');
    }
}
