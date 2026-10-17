<?php

namespace Tests\Feature;

use App\Models\HotelAdmin;
use App\Models\PaymentGateway;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DistributorSalePaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $distributor;
    protected HotelAdmin $hotel;
    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'distributor']);

        $this->distributor = User::factory()->create();
        $this->distributor->assignRole('distributor');

        $this->plan = Plan::create([
            'name' => 'Gold Plan',
            'room_count' => 50,
            'price' => '2500.00',
            'status' => true,
        ]);

        $this->hotel = HotelAdmin::create([
            'distributor_id' => $this->distributor->id,
            'owner_name' => 'Firoz Test',
            'email' => 'firoztest@hotel.com',
            'password' => bcrypt('password'),
            'phone' => '9876543210',
            'hotel_name' => 'Firoz Hotel Test',
            'hotel_location' => 'Lucknow',
            'city' => 'Lucknow',
            'state' => 'UP',
            'pincode' => '226001',
            'room_count' => 0,
            'status' => true,
            'approval_status' => 'approved',
            'payment_status' => 'unpaid',
        ]);

        PaymentGateway::create([
            'name' => 'Razorpay',
            'code' => 'razorpay',
            'is_enabled' => true,
            'mode' => 'sandbox',
        ]);
    }

    public function test_distributor_sale_page_displays_active_payment_gateway_and_no_offline_methods(): void
    {
        $response = $this->actingAs($this->distributor)
                         ->get(route('distributor.sales.create'));

        $response->assertStatus(200);
        $response->assertSee('Authorized Payment Channel');
        $response->assertSee('Razorpay');
        $response->assertSee('Active Gateway');
        // Offline / unverified options must not be present
        $response->assertDontSee('Cash Payment');
        $response->assertDontSee('Cheque / DD');
        $response->assertDontSee('Bank NEFT / RTGS / IMPS');
    }

    public function test_offline_payment_methods_are_strictly_rejected(): void
    {
        $response = $this->actingAs($this->distributor)
                         ->post(route('distributor.sales.store'), [
                             'hotel_id' => $this->hotel->id,
                             'plan_id' => $this->plan->id,
                             'duration_months' => 1,
                             'amount' => 2500.00,
                             'payment_method' => 'cash',
                         ]);

        $response->assertSessionHasErrors(['payment_method']);
        $this->hotel->refresh();
        $this->assertEquals('unpaid', $this->hotel->payment_status);
        $this->assertNull($this->hotel->plan_id);
    }

    public function test_unverified_gateway_payment_without_credentials_is_rejected(): void
    {
        $response = $this->actingAs($this->distributor)
                         ->post(route('distributor.sales.store'), [
                             'hotel_id' => $this->hotel->id,
                             'plan_id' => $this->plan->id,
                             'duration_months' => 1,
                             'amount' => 2500.00,
                             'payment_method' => 'online_gateway',
                             // missing razorpay_order_id and razorpay_payment_id
                         ]);

        $this->hotel->refresh();
        $this->assertEquals('unpaid', $this->hotel->payment_status);
        $this->assertNull($this->hotel->plan_id);
    }

    public function test_verified_online_gateway_payment_activates_hotel_plan(): void
    {
        $response = $this->actingAs($this->distributor)
                         ->post(route('distributor.sales.store'), [
                             'hotel_id' => $this->hotel->id,
                             'plan_id' => $this->plan->id,
                             'duration_months' => 1,
                             'amount' => 2500.00,
                             'payment_method' => 'online_gateway',
                             'razorpay_order_id' => 'order_test123',
                             'razorpay_payment_id' => 'pay_test123',
                             'razorpay_signature' => 'mock_sig',
                         ]);

        $response->assertRedirect(route('distributor.sales.index'));
        $this->hotel->refresh();
        $this->assertEquals('paid', $this->hotel->payment_status);
        $this->assertEquals($this->plan->id, $this->hotel->plan_id);
        $this->assertEquals(50, $this->hotel->allowed_device_limit);
        $this->assertNotNull($this->hotel->purchase_date);
        $this->assertNotNull($this->hotel->expiry_date);
    }
}
