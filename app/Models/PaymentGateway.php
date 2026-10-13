<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $table = 'payment_gateways';

    protected $fillable = [
        'name',
        'code',
        'is_enabled',
        'api_key',
        'api_secret',
        'merchant_id',
        'mode',
        'extra_config',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'extra_config' => 'array',
        'api_key' => 'encrypted',
        'api_secret' => 'encrypted',
    ];

    /**
     * Helper to get gateway configuration by code.
     */
    public static function getForCode(string $code): ?self
    {
        return self::where('code', $code)->first();
    }

    public static function isEnabled(string $code): bool
    {
        $gateway = self::getForCode($code);
        return $gateway && $gateway->is_enabled;
    }
}
