<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::updateOrCreate(
            ['code' => 'icici'],
            [
                'name' => 'ICICI Payment Gateway',
                'is_enabled' => false, // Disabled by default as requested
                'mode' => 'sandbox',
                'api_key' => null,
                'api_secret' => null,
                'merchant_id' => null,
            ]
        );
    }
}
