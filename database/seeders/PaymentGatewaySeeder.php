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
        $gateways = [
            [
                'name' => 'ICICI Payment Gateway',
                'code' => 'icici',
                'is_enabled' => true,
                'mode' => 'sandbox',
            ],
            [
                'name' => 'Razorpay',
                'code' => 'razorpay',
                'is_enabled' => false,
                'mode' => 'sandbox',
            ],
            [
                'name' => 'PayU',
                'code' => 'payu',
                'is_enabled' => false,
                'mode' => 'sandbox',
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::updateOrCreate(
                ['code' => $gateway['code']],
                $gateway
            );
        }
    }
}
