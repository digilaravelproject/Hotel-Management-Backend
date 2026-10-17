<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\PaymentGateway;
use App\Services\PaymentGateways\IciciGatewayStrategy;
use App\Services\PaymentGateways\RazorpayGatewayStrategy;
use App\Services\PaymentGateways\PayUGatewayStrategy;
use Exception;

class PaymentManager
{
    /**
     * Get the active gateway strategy based on database configuration.
     */
    public static function active(): PaymentGatewayInterface
    {
        $activeRecord = PaymentGateway::where('is_enabled', true)->first();

        $code = $activeRecord ? $activeRecord->code : 'icici';

        return self::resolve($code);
    }

    /**
     * Resolve a specific gateway strategy by code.
     */
    public static function resolve(string $code): PaymentGatewayInterface
    {
        return match (strtolower($code)) {
            'razorpay' => app(RazorpayGatewayStrategy::class),
            'payu' => app(PayUGatewayStrategy::class),
            'icici' => app(IciciGatewayStrategy::class),
            default => throw new Exception("Unsupported payment gateway code: {$code}"),
        };
    }
}
