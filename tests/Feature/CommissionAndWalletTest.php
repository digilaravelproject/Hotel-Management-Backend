<?php

namespace Tests\Feature;

use App\Models\CommissionSetting;
use App\Models\DistributorSale;
use App\Models\DistributorWallet;
use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\SuperAdmin;
use App\Models\User;
use App\Models\WithdrawalRequest;
use App\Services\CommissionService;
use App\Services\HotelService;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommissionAndWalletTest extends TestCase
{
    use RefreshDatabase;

    protected User $distributor;
    protected SuperAdmin $superAdmin;
    protected Plan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'distributor']);

        $this->distributor = User::factory()->create([
            'name' => 'Test Partner',
            'email' => 'partner@test.com',
            'commission_percentage' => null, // Global default applies
        ]);
        $this->distributor->assignRole('distributor');

        $this->superAdmin = SuperAdmin::create([
            'name' => 'Super Administrator',
            'email' => 'admin@hotelmanagement.test',
            'password' => bcrypt('password123'),
        ]);

        $this->plan = Plan::create([
            'name' => 'Diamond Premium Plan',
            'room_count' => 100,
            'price' => '10000.00',
            'status' => true,
        ]);

        CommissionSetting::create([
            'default_percentage' => 10.00,
        ]);
    }

    public function test_global_commission_setting_is_calculated_for_distributor(): void
    {
        $commissionService = app(CommissionService::class);
        $result = $commissionService->calculateCommission($this->distributor, 10000.00);

        $this->assertEquals(10.00, $result['rate']);
        $this->assertFalse($result['is_override']);
        $this->assertEquals(1000.00, $result['commission_amount']);
    }

    public function test_individual_commission_override_takes_precedence_over_global(): void
    {
        $this->distributor->update(['commission_percentage' => 25.00]);

        $commissionService = app(CommissionService::class);
        $result = $commissionService->calculateCommission($this->distributor, 10000.00);

        $this->assertEquals(25.00, $result['rate']);
        $this->assertTrue($result['is_override']);
        $this->assertEquals(2500.00, $result['commission_amount']);
    }

    public function test_package_sale_automatically_credits_commission_to_wallet(): void
    {
        $hotel = HotelAdmin::create([
            'owner_name' => 'Owner A',
            'email' => 'hotelA@test.com',
            'password' => bcrypt('password'),
            'phone' => '9999999999',
            'hotel_name' => 'Hotel Alpha',
            'hotel_location' => 'Mumbai',
            'city' => 'Mumbai',
            'room_count' => 10,
            'distributor_id' => $this->distributor->id,
            'plan_id' => $this->plan->id,
            'status' => true,
        ]);

        $hotelService = app(HotelService::class);
        $saleResult = $hotelService->assignPlanAndRecordSale(
            $hotel,
            $this->plan,
            1,
            ['amount' => 10000.00, 'notes' => 'Test sale'],
            $this->distributor->id
        );

        $wallet = DistributorWallet::where('distributor_id', $this->distributor->id)->first();
        $this->assertNotNull($wallet);
        // 10% of 10000 = 1000
        $this->assertEquals(1000.00, (float) $wallet->balance);
        $this->assertEquals(1000.00, (float) $wallet->total_earned);

        $transaction = $this->distributor->walletTransactions()->latest()->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('credit', $transaction->type);
        $this->assertEquals(1000.00, (float) $transaction->amount);
    }

    public function test_distributor_can_request_withdrawal_and_funds_are_locked(): void
    {
        $walletService = app(WalletService::class);
        // Credit 5000 to wallet
        $walletService->credit($this->distributor->id, 5000.00, 'sale', null, 'Initial credit');

        $response = $this->actingAs($this->distributor)
                         ->post(route('distributor.wallet.withdraw'), [
                             'amount' => 2000.00,
                             'payment_method' => 'upi',
                             'upi_id' => 'partner@okaxis',
                         ]);

        $response->assertRedirect(route('distributor.wallet.index'));

        $wallet = DistributorWallet::where('distributor_id', $this->distributor->id)->first();
        $this->assertEquals(5000.00, (float) $wallet->balance);
        $this->assertEquals(2000.00, (float) $wallet->locked_balance);
        $this->assertEquals(3000.00, (float) $wallet->available_balance);

        $request = WithdrawalRequest::where('distributor_id', $this->distributor->id)->first();
        $this->assertNotNull($request);
        $this->assertEquals('pending', $request->status);
        $this->assertEquals(2000.00, (float) $request->amount);
    }

    public function test_distributor_cannot_withdraw_more_than_available_balance(): void
    {
        $walletService = app(WalletService::class);
        $walletService->credit($this->distributor->id, 1000.00, 'sale', null, 'Initial credit');

        $response = $this->actingAs($this->distributor)
                         ->post(route('distributor.wallet.withdraw'), [
                             'amount' => 1500.00,
                             'payment_method' => 'upi',
                             'upi_id' => 'partner@okaxis',
                         ]);

        $response->assertSessionHasErrors('amount');
    }

    public function test_admin_approval_deducts_balance_and_records_transaction_number(): void
    {
        $walletService = app(WalletService::class);
        $walletService->credit($this->distributor->id, 5000.00, 'sale', null, 'Initial credit');
        $withdrawal = $walletService->requestWithdrawal($this->distributor->id, 2000.00, 'upi', 'partner@upi');

        $response = $this->actingAs($this->superAdmin, 'super_admin')
                         ->post(route('super-admin.withdrawals.approve', $withdrawal->id), [
                             'transaction_number' => 'UTR987654321',
                             'admin_notes' => 'Settled via Corporate NetBanking',
                         ]);

        $response->assertSessionHas('success');

        $wallet = DistributorWallet::where('distributor_id', $this->distributor->id)->first();
        $this->assertEquals(3000.00, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->locked_balance);
        $this->assertEquals(2000.00, (float) $wallet->total_withdrawn);

        $withdrawal->refresh();
        $this->assertEquals('approved', $withdrawal->status);
        $this->assertEquals('UTR987654321', $withdrawal->transaction_number);

        $debitTx = $this->distributor->walletTransactions()->latest('id')->first();
        $this->assertEquals('debit', $debitTx->type);
        $this->assertEquals(2000.00, (float) $debitTx->amount);
    }

    public function test_admin_rejection_releases_locked_balance(): void
    {
        $walletService = app(WalletService::class);
        $walletService->credit($this->distributor->id, 5000.00, 'sale', null, 'Initial credit');
        $withdrawal = $walletService->requestWithdrawal($this->distributor->id, 2000.00, 'upi', 'partner@upi');

        $response = $this->actingAs($this->superAdmin, 'super_admin')
                         ->post(route('super-admin.withdrawals.reject', $withdrawal->id), [
                             'admin_notes' => 'Invalid UPI VPA address provided.',
                         ]);

        $response->assertSessionHas('success');

        $wallet = DistributorWallet::where('distributor_id', $this->distributor->id)->first();
        $this->assertEquals(5000.00, (float) $wallet->balance);
        $this->assertEquals(0.00, (float) $wallet->locked_balance);
        $this->assertEquals(5000.00, (float) $wallet->available_balance);

        $withdrawal->refresh();
        $this->assertEquals('rejected', $withdrawal->status);
        $this->assertEquals('Invalid UPI VPA address provided.', $withdrawal->admin_notes);
    }
}
