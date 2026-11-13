<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\SuperAdmin;
use App\Services\TurnstileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TurnstileIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_when_disabled_form_submits_normally_without_turnstile_token(): void
    {
        $setting = AppSetting::first() ?? new AppSetting();
        $setting->fill([
            'app_name' => 'PaxTV',
            'turnstile_enabled' => false,
            'turnstile_site_key' => null,
            'turnstile_secret_key' => null,
        ])->save();
        AppSetting::flushSettingsCache();

        $service = app(TurnstileService::class);
        $this->assertFalse($service->isEnabled());
        $this->assertTrue($service->verify(null));

        $response = $this->postJson(route('contact.submit'), [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'phone' => '+91 99999 88888',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_super_admin_can_save_turnstile_settings_with_encrypted_secret(): void
    {
        $superAdmin = SuperAdmin::create([
            'name' => 'Super User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $setting = AppSetting::first() ?? new AppSetting();
        $setting->app_name = 'PaxTV';
        $setting->save();
        AppSetting::flushSettingsCache();

        $response = $this->actingAs($superAdmin, 'super_admin')
            ->post(route('super-admin.settings.update'), [
                'app_name' => 'PaxTV Updated',
                'turnstile_enabled' => 1,
                'turnstile_site_key' => '0x4AAAAAAABcdef12345678',
                'turnstile_secret_key' => '0x4AAAAAAABcdefSec987654321',
            ]);

        $response->assertRedirect(route('super-admin.settings.index'));

        $setting->refresh();
        $this->assertTrue((bool) $setting->turnstile_enabled);
        $this->assertEquals('0x4AAAAAAABcdef12345678', $setting->turnstile_site_key);
        $this->assertEquals('0x4AAAAAAABcdefSec987654321', $setting->turnstile_secret_key);

        // Verify stored in DB encrypted
        $rawInDb = \Illuminate\Support\Facades\DB::table('app_settings')->where('id', $setting->id)->value('turnstile_secret_key');
        $this->assertNotEquals('0x4AAAAAAABcdefSec987654321', $rawInDb);
    }

    public function test_when_enabled_invalid_turnstile_token_is_rejected(): void
    {
        $setting = AppSetting::first() ?? new AppSetting();
        $setting->fill([
            'app_name' => 'PaxTV',
            'turnstile_enabled' => true,
            'turnstile_site_key' => '0x4AAAAAAABcdef12345678',
            'turnstile_secret_key' => '0x4AAAAAAABcdefSec987654321',
        ])->save();
        AppSetting::flushSettingsCache();

        Http::fake([
            'challenges.cloudflare.com/*' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response'],
            ], 200),
        ]);

        $response = $this->postJson(route('contact.submit'), [
            'name' => 'Bad Bot',
            'email' => 'bot@spammer.test',
            'phone' => '+91 11111 22222',
            'cf-turnstile-response' => 'fake_invalid_token',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Security bot verification failed. Please refresh and try again.',
        ]);
    }

    public function test_when_enabled_valid_turnstile_token_passes_and_saves_inquiry(): void
    {
        $setting = AppSetting::first() ?? new AppSetting();
        $setting->fill([
            'app_name' => 'PaxTV',
            'turnstile_enabled' => true,
            'turnstile_site_key' => '0x4AAAAAAABcdef12345678',
            'turnstile_secret_key' => '0x4AAAAAAABcdefSec987654321',
        ])->save();
        AppSetting::flushSettingsCache();

        Http::fake([
            'challenges.cloudflare.com/*' => Http::response([
                'success' => true,
                'challenge_ts' => now()->toIso8601String(),
                'hostname' => 'paxtvlauncher.com',
            ], 200),
        ]);

        $response = $this->postJson(route('contact.submit'), [
            'name' => 'Genuine Client',
            'email' => 'client@hotel.test',
            'phone' => '+91 98765 43210',
            'cf-turnstile-response' => 'valid_turnstile_token_from_browser',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('contact_inquiries', [
            'email' => 'client@hotel.test',
        ]);
    }

    public function test_cloudflare_network_downtime_fails_open_gracefully(): void
    {
        $setting = AppSetting::first() ?? new AppSetting();
        $setting->fill([
            'app_name' => 'PaxTV',
            'turnstile_enabled' => true,
            'turnstile_site_key' => '0x4AAAAAAABcdef12345678',
            'turnstile_secret_key' => '0x4AAAAAAABcdefSec987654321',
        ])->save();
        AppSetting::flushSettingsCache();

        // Cloudflare returns 500 error or connection failure
        Http::fake([
            'challenges.cloudflare.com/*' => Http::response('Server Error', 500),
        ]);

        $response = $this->postJson(route('contact.submit'), [
            'name' => 'Real User During Cloudflare Glitch',
            'email' => 'realuser@hotel.test',
            'phone' => '+91 98765 00000',
            'cf-turnstile-response' => 'some_token',
        ]);

        // Fails open gracefully so customer isn't blocked by cloudflare downtime
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}

