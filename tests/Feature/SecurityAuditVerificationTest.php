<?php

namespace Tests\Feature;

use App\Helpers\ImageHelper;
use App\Models\ConnectedDevice;
use App\Models\FlightSetting;
use App\Models\HotelAdmin;
use App\Models\MailSetting;
use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\SuperAdmin;
use App\Models\TvPairSession;
use App\Models\User;
use App\Services\HotelRegistrationService;
use App\Services\PaymentManager;
use App\Services\PaymentOrderService;
use App\Services\TvLoginService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityAuditVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function createActivePlan(): Plan
    {
        return Plan::create([
            'name' => 'Security Audit Pro Plan',
            'room_count' => 10,
            'price' => '1000.00',
            'billing_cycle' => 'monthly',
            'is_hidden' => false,
            'status' => true,
        ]);
    }

    private function createHotel(Plan $plan, array $overrides = []): HotelAdmin
    {
        return HotelAdmin::create(array_merge([
            'owner_name' => 'Audit Owner',
            'email' => 'owner_' . Str::random(5) . '@hotel.test',
            'password' => bcrypt('SecretPass#123'),
            'phone' => '+91 98765 00001',
            'hotel_name' => 'Audit Palace',
            'hotel_location' => 'Mumbai',
            'city' => 'Mumbai',
            'room_count' => 10,
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'license_key' => 'LIC-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            'approval_status' => 'approved',
            'status' => true,
            'expiry_date' => now()->addMonth(),
        ], $overrides));
    }

    public function test_payment_order_service_enforces_binding_and_blocks_replay(): void
    {
        $plan = $this->createActivePlan();

        \Illuminate\Support\Facades\Http::fake([
            'api.razorpay.com/*' => \Illuminate\Support\Facades\Http::response([
                'id' => 'order_rzp_audit_1',
                'amount' => 500000,
                'currency' => 'INR',
                'status' => 'created',
            ], 200),
        ]);

        PaymentGateway::create([
            'name' => 'Razorpay',
            'code' => 'razorpay',
            'is_enabled' => true,
            'mode' => 'sandbox',
            'api_key' => 'rzp_test_audit',
            'api_secret' => 'rzp_secret_audit',
        ]);

        /** @var HotelRegistrationService $regService */
        $regService = app(HotelRegistrationService::class);
        $order = $regService->createRegistrationOrder([
            'plan_id' => $plan->id,
            'room_count' => 5,
            'duration_months' => 1,
            'email' => 'reg@hotel.test',
            'phone' => '9876543210',
            'hotel_name' => 'Reg Test Hotel',
        ]);

        $orderId = $order['order_id'];
        $expectedAmount = $order['amount'];

        $tx = PaymentTransaction::where('transaction_id', $orderId)->first();
        $this->assertNotNull($tx);
        $this->assertSame('pending', $tx->status);

        $gateway = PaymentManager::active();
        /** @var PaymentOrderService $orderService */
        $orderService = app(PaymentOrderService::class);

        $signature = hash_hmac('sha256', $orderId . '|pay_test_1', 'rzp_secret_audit');
        $payload = [
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => 'pay_test_1',
            'razorpay_signature' => $signature,
        ];

        // Purpose tamper check (wrong purpose rejected while still pending)
        try {
            $orderService->consumeVerifiedPayment($gateway, $orderId, 'pay_test_1', $payload, 'distributor_sale', [
                'plan_id' => $plan->id,
                'expected_amount' => $expectedAmount,
            ]);
            $this->fail('Expected purpose mismatch exception.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('purpose mismatch', strtolower($e->getMessage()));
        }

        // Amount tamper check
        try {
            $orderService->consumeVerifiedPayment($gateway, $orderId, 'pay_test_1', $payload, 'hotel_registration', [
                'plan_id' => $plan->id,
                'expected_amount' => 1.00,
            ]);
            $this->fail('Expected amount mismatch exception.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('tampered', strtolower($e->getMessage()));
        }

        // Valid consumption succeeds once
        $consumed = $orderService->consumeVerifiedPayment($gateway, $orderId, 'pay_test_1', $payload, 'hotel_registration', [
            'plan_id' => $plan->id,
            'expected_amount' => $expectedAmount,
        ]);
        $this->assertEquals($expectedAmount, $consumed['amount']);

        // Replay must fail cleanly
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('already been processed');
        $orderService->consumeVerifiedPayment($gateway, $orderId, 'pay_test_1', $payload, 'hotel_registration', [
            'plan_id' => $plan->id,
            'expected_amount' => $expectedAmount,
        ]);
    }

    public function test_tv_pair_status_enforces_device_id_and_blocks_token_hijacking(): void
    {
        $plan = $this->createActivePlan();
        $hotel = $this->createHotel($plan);

        $session = TvPairSession::create([
            'pair_code' => 'ABCD-1234',
            'device_id' => 'LEGIT-TV-01',
            'mac_address' => 'AA:BB:CC:DD:EE:01',
            'status' => 'paired',
            'hotel_admin_id' => $hotel->id,
            'room_no' => '101',
            'expires_at' => now()->addMinutes(10),
        ]);

        ConnectedDevice::create([
            'hotel_admin_id' => $hotel->id,
            'room_no' => '101',
            'device_id' => 'LEGIT-TV-01',
            'mac_address' => 'AA:BB:CC:DD:EE:01',
            'api_token' => 'secret-tv-token-01',
            'is_demo' => false,
        ]);

        // Missing device_id rejected
        $this->postJson('/api/tv/pair-status', ['pair_code' => 'ABCD-1234'])
            ->assertStatus(422);

        // Attacker with wrong device_id blocked
        $this->postJson('/api/tv/pair-status', [
            'pair_code' => 'ABCD-1234',
            'device_id' => 'ATTACKER-TV-99',
        ])->assertStatus(403);

        // Legitimate device receives token & stays valid status 'paired'
        $this->postJson('/api/tv/pair-status', [
            'pair_code' => 'ABCD-1234',
            'device_id' => 'LEGIT-TV-01',
        ])->assertStatus(200)
          ->assertJsonPath('status', true)
          ->assertSee('secret-tv-token-01');

        $this->assertSame('paired', $session->fresh()->status);
    }

    public function test_tv_login_blocks_cross_hotel_device_takeover_and_inactive_hotel_token(): void
    {
        $plan = $this->createActivePlan();
        $hotelA = $this->createHotel($plan, ['license_key' => 'HOTA-1111-2222-3333']);
        $hotelB = $this->createHotel($plan, ['license_key' => 'HOTB-1111-2222-3333']);

        $device = ConnectedDevice::create([
            'hotel_admin_id' => $hotelA->id,
            'room_no' => '202',
            'device_id' => 'SHARED-HW-ID',
            'mac_address' => '11:22:33:44:55:66',
            'api_token' => 'token-hotel-a',
            'is_demo' => false,
        ]);

        // Hotel B attempting to claim Hotel A's bound deviceId is rejected (403)
        $this->postJson('/api/tv/login', [
            'license_key' => $hotelB->license_key,
            'room_no' => '303',
            'deviceId' => 'SHARED-HW-ID',
            'macAddress' => '11:22:33:44:55:66',
        ])->assertStatus(403);

        $this->assertSame($hotelA->id, $device->fresh()->hotel_admin_id);

        // Deactivating Hotel A immediately invalidates device API calls
        $hotelA->update(['status' => false]);
        $this->withHeader('Authorization', 'Bearer token-hotel-a')
            ->getJson('/api/tv/template/check-version')
            ->assertStatus(403);
    }

    public function test_two_factor_verification_is_isolated_per_guard_and_user(): void
    {
        $plan = $this->createActivePlan();
        $hotel = $this->createHotel($plan, ['google2fa_enabled' => true]);
        $superAdmin = SuperAdmin::create([
            'name' => 'Root Audit',
            'email' => 'root@audit.test',
            'password' => bcrypt('SecretPass#123'),
            'google2fa_enabled' => true,
            'google2fa_secret' => encrypt('JBSWY3DPEHPK3PXP'),
        ]);

        // Hotel 2FA verified session flag must NOT grant access to SuperAdmin 2FA routes
        $response = $this->actingAs($superAdmin, 'super_admin')
            ->withSession([
                '2fa_verified:hotel_admin:' . $hotel->id => true,
                '2fa_verified' => true, // legacy global flag ignored
            ])
            ->get(route('super-admin.dashboard'));

        $response->assertRedirect(route('2fa.verify'));
    }

    public function test_forgot_password_otp_is_hashed_and_does_not_enumerate_emails(): void
    {
        Mail::fake();
        $plan = $this->createActivePlan();
        $hotel = $this->createHotel($plan, ['email' => 'real@hotel.test']);

        // Unregistered email receives identical redirect with no existence error
        $unknownResp = $this->post(route('hotel.forgot-password'), [
            'email' => 'nonexistent@hotel.test',
        ]);
        $unknownResp->assertRedirect(route('hotel.reset-password'));
        $unknownResp->assertSessionHasNoErrors();

        // Registered email stores bcrypt hash, never plaintext 6-digit OTP
        $knownResp = $this->post(route('hotel.forgot-password'), [
            'email' => 'REAL@hotel.test',
        ]);
        $knownResp->assertRedirect(route('hotel.reset-password'));

        $hotel->refresh();
        $this->assertNotNull($hotel->otp_code);
        $this->assertNotEquals(6, strlen($hotel->otp_code));
        $this->assertTrue(Hash::info($hotel->otp_code)['algoName'] !== 'unknown');
    }

    public function test_super_admin_user_store_blocks_super_admin_role_escalation(): void
    {
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'distributor', 'guard_name' => 'web']);

        $superAdmin = SuperAdmin::create([
            'name' => 'Super Owner',
            'email' => 'super@audit.test',
            'password' => bcrypt('SecretPass#123'),
        ]);

        $response = $this->actingAs($superAdmin, 'super_admin')
            ->withSession(['2fa_verified:super_admin:' . $superAdmin->id => true])
            ->post(route('super-admin.users.store'), [
                'name' => 'Backdoor User',
                'email' => 'backdoor@audit.test',
                'password' => 'StrongPass#123',
                'password_confirmation' => 'StrongPass#123',
                'roles' => ['super_admin'],
            ]);

        $response->assertSessionHasErrors('roles.0');
        $this->assertDatabaseMissing('users', ['email' => 'backdoor@audit.test']);
    }

    public function test_state_changing_get_routes_return_405(): void
    {
        $superAdmin = SuperAdmin::create([
            'name' => 'Super Owner',
            'email' => 'csrf@audit.test',
            'password' => bcrypt('SecretPass#123'),
        ]);

        $this->actingAs($superAdmin, 'super_admin')
            ->withSession(['2fa_verified:super_admin:' . $superAdmin->id => true])
            ->get(route('super-admin.hotels.toggle-status', ['id' => 1]))
            ->assertStatus(405);

        $this->actingAs($superAdmin, 'super_admin')
            ->withSession(['2fa_verified:super_admin:' . $superAdmin->id => true])
            ->get(route('super-admin.flights.refresh', ['iata' => 'BOM']))
            ->assertStatus(405);

        $plan = $this->createActivePlan();
        $hotel = $this->createHotel($plan);

        $this->actingAs($hotel, 'hotel_admin')
            ->withSession(['2fa_verified:hotel_admin:' . $hotel->id => true])
            ->get(route('hotel.amenities.toggle-status', ['id' => 1]))
            ->assertStatus(405);
    }

    public function test_svg_sanitizer_strips_xss_vectors_and_template_upload_blocks_zip_slip(): void
    {
        $dirtySvg = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(2)</script><rect width="10" height="10" style="fill:red" onclick="alert(3)"/><foreignObject><body onload="alert(4)"></body></foreignObject></svg>';
        $cleaned = ImageHelper::sanitizeSvg($dirtySvg);

        $this->assertStringNotContainsString('<script', strtolower($cleaned));
        $this->assertStringNotContainsString('onload', strtolower($cleaned));
        $this->assertStringNotContainsString('onclick', strtolower($cleaned));
        $this->assertStringNotContainsString('foreignobject', strtolower($cleaned));
        $this->assertStringContainsString('<rect', $cleaned);

        // Verify ZIP Slip / PHP file rejection in TemplateController
        $superAdmin = SuperAdmin::create([
            'name' => 'Theme Admin',
            'email' => 'theme@audit.test',
            'password' => bcrypt('SecretPass#123'),
        ]);

        $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'evil_theme_' . uniqid() . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('../pwn.php', '<?php echo 1; ?>');
        $zip->close();

        $uploaded = new UploadedFile($zipPath, 'evil_theme.zip', 'application/zip', null, true);

        $response = $this->actingAs($superAdmin, 'super_admin')
            ->withSession(['2fa_verified:super_admin:' . $superAdmin->id => true])
            ->post(route('super-admin.templates.store'), [
                'theme_id' => 999,
                'theme_name' => 'Evil Theme',
                'template_file' => $uploaded,
            ]);

        $response->assertSessionHas('error');
        $this->assertFileDoesNotExist(public_path('pwn.php'));
        @unlink($zipPath);
    }

    public function test_secrets_are_encrypted_at_rest_hidden_in_arrays_and_legacy_plaintext_compatible(): void
    {
        // Legacy plaintext row compatibility check
        DB::table('mail_settings')->insert([
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.example.com',
            'mail_port' => 587,
            'mail_password' => 'legacy-plain-smtp-pass',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $mail = MailSetting::first();
        $this->assertSame('legacy-plain-smtp-pass', $mail->mail_password);
        $this->assertArrayNotHasKey('mail_password', $mail->toArray());

        // Saving encrypts at rest
        $mail->update(['mail_password' => 'new-super-secret']);
        $rawMailPass = DB::table('mail_settings')->where('id', $mail->id)->value('mail_password');
        $this->assertNotSame('new-super-secret', $rawMailPass);
        $this->assertSame('new-super-secret', $mail->fresh()->mail_password);

        // FlightSetting API key encrypted & hidden
        $flight = FlightSetting::create([
            'provider' => 'airlabs',
            'api_key' => 'flight-secret-xyz',
            'cache_ttl_minutes' => 30,
            'is_active' => true,
        ]);
        $rawFlightKey = DB::table('flight_settings')->where('id', $flight->id)->value('api_key');
        $this->assertNotSame('flight-secret-xyz', $rawFlightKey);
        $this->assertSame('flight-secret-xyz', $flight->fresh()->api_key);
        $this->assertArrayNotHasKey('api_key', $flight->toArray());

        // PaymentGateway extra_config secret encryption + legacy read compatibility
        $gw = PaymentGateway::create([
            'name' => 'PayU',
            'code' => 'payu',
            'is_enabled' => true,
            'mode' => 'live',
            'api_key' => 'live-key-1',
            'api_secret' => 'live-salt-1',
            'extra_config' => [
                'live' => ['api_key' => 'live-key-1', 'api_secret' => 'live-salt-1'],
            ],
        ]);
        $rawExtra = DB::table('payment_gateways')->where('id', $gw->id)->value('extra_config');
        $this->assertStringNotContainsString('live-salt-1', (string) $rawExtra);
        $this->assertSame('live-salt-1', $gw->fresh()->getLiveSecret());
        $this->assertArrayNotHasKey('extra_config', $gw->toArray());
    }

    public function test_git_pull_endpoint_fails_closed_for_unauthorized_ip(): void
    {
        $scriptPath = base_path('public/git_pull.php');
        $this->assertFileExists($scriptPath);

        $probePhp = <<<'PHP'
$_SERVER['REMOTE_ADDR'] = '198.51.100.44';
$_SERVER['REQUEST_METHOD'] = 'GET';
require %s;
PHP;
        $tmpScript = tempnam(sys_get_temp_dir(), 'gitpull_probe_');
        file_put_contents($tmpScript, "<?php\n" . sprintf($probePhp, var_export($scriptPath, true)));

        $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($tmpScript) . ' 2>&1';
        $output = shell_exec($cmd);
        @unlink($tmpScript);

        $this->assertIsString($output);
        $this->assertStringContainsString('403 Access Denied', $output);
    }
}
