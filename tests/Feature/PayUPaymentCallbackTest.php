<?php

namespace Tests\Feature;

use App\Models\HotelAdmin;
use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\User;
use App\Services\PaymentGateways\PayUGatewayStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayUPaymentCallbackTest extends TestCase
{
    use RefreshDatabase;

    protected User $distributor;
    protected HotelAdmin $hotel;
    protected Plan $plan;
    protected PaymentGateway $payuGateway;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'distributor']);

        $this->distributor = User::factory()->create();
        $this->distributor->assignRole('distributor');

        $this->plan = Plan::create([
            'name' => 'Premium Diamond Plan',
            'room_count' => 100,
            'price' => '5000.00',
            'status' => true,
        ]);

        $this->hotel = HotelAdmin::create([
            'distributor_id' => $this->distributor->id,
            'owner_name' => 'PayU Hotel Owner',
            'email' => 'payuowner@hotel.com',
            'password' => bcrypt('password'),
            'phone' => '9876500000',
            'hotel_name' => 'PayU Grand Resort',
            'hotel_location' => 'Goa',
            'city' => 'Goa',
            'state' => 'Goa',
            'pincode' => '403001',
            'room_count' => 0,
            'status' => true,
            'approval_status' => 'approved',
            'payment_status' => 'unpaid',
        ]);

        $this->payuGateway = PaymentGateway::create([
            'name' => 'PayU',
            'code' => 'payu',
            'api_key' => 'test_payu_key',
            'api_secret' => 'test_payu_salt',
            'is_enabled' => true,
            'mode' => 'sandbox',
        ]);
    }

    public function test_payu_create_order_generates_surl_and_furl_without_crash(): void
    {
        $strategy = app(PayUGatewayStrategy::class);
        $order = $strategy->createOrder(5000.00, 'TEST-ORD-1', [
            'name' => 'Test User',
            'email' => 'test@user.com',
            'phone' => '9999999999',
            'udf1' => (string) $this->hotel->id,
            'udf2' => (string) $this->plan->id,
            'udf3' => '1',
            'udf4' => (string) $this->distributor->id,
            'udf5' => 'distributor_sale',
        ]);

        $this->assertTrue($order['success']);
        $this->assertArrayHasKey('payu_params', $order);
        $this->assertEquals(route('payment.success'), $order['payu_params']['surl']);
        $this->assertEquals(route('payment.failure'), $order['payu_params']['furl']);
    }

    public function test_payu_success_callback_activates_distributor_sale(): void
    {
        $txnid = 'payu_txn_' . uniqid();
        $amount = '5000.00';
        $status = 'success';
        $productInfo = 'Plan: Premium Diamond Plan';
        $firstName = 'Test User';
        $email = 'test@user.com';
        $salt = 'test_payu_salt';
        $key = 'test_payu_key';

        $udf1 = (string) $this->hotel->id;
        $udf2 = (string) $this->plan->id;
        $udf3 = '1';
        $udf4 = (string) $this->distributor->id;
        $udf5 = 'distributor_sale';

        // Calculate valid reverse hash
        $hashString = "{$salt}|{$status}||||||{$udf5}|{$udf4}|{$udf3}|{$udf2}|{$udf1}|{$email}|{$firstName}|{$productInfo}|{$amount}|{$txnid}|{$key}";
        $hash = strtolower(hash('sha512', $hashString));

        $response = $this->post(route('payment.success'), [
            'status' => $status,
            'txnid' => $txnid,
            'amount' => $amount,
            'productinfo' => $productInfo,
            'firstname' => $firstName,
            'email' => $email,
            'hash' => $hash,
            'udf1' => $udf1,
            'udf2' => $udf2,
            'udf3' => $udf3,
            'udf4' => $udf4,
            'udf5' => $udf5,
        ]);

        $response->assertRedirect(route('distributor.sales.index'));
        $this->hotel->refresh();
        $this->assertEquals('paid', $this->hotel->payment_status);
        $this->assertEquals($this->plan->id, $this->hotel->plan_id);
        $this->assertEquals(100, $this->hotel->allowed_device_limit);

        $this->assertDatabaseHas('payment_transactions', [
            'transaction_id' => $txnid,
            'gateway_code' => 'payu',
            'status' => 'success',
        ]);
    }

    public function test_payu_failure_callback_redirects_with_error(): void
    {
        $txnid = 'payu_fail_' . uniqid();

        $response = $this->post(route('payment.failure'), [
            'status' => 'failure',
            'txnid' => $txnid,
            'amount' => '5000.00',
            'error_Message' => 'User cancelled transaction on PayU',
            'udf1' => (string) $this->hotel->id,
            'udf2' => (string) $this->plan->id,
            'udf4' => (string) $this->distributor->id,
            'udf5' => 'distributor_sale',
        ]);

        $response->assertRedirect(route('distributor.sales.index'));
        $response->assertSessionHas('error');

        $this->hotel->refresh();
        $this->assertEquals('unpaid', $this->hotel->payment_status);

        $this->assertDatabaseHas('payment_transactions', [
            'transaction_id' => $txnid,
            'gateway_code' => 'payu',
            'status' => 'failed',
        ]);
    }
}
