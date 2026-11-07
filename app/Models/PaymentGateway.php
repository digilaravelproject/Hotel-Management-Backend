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
     * Helper getters for mode-specific credentials.
     */
    public function getSandboxKey(): ?string
    {
        return $this->extra_config['sandbox']['api_key'] 
            ?? ($this->mode === 'sandbox' ? $this->api_key : null);
    }

    public function getSandboxSecret(): ?string
    {
        return $this->extra_config['sandbox']['api_secret'] 
            ?? ($this->mode === 'sandbox' ? $this->api_secret : null);
    }

    public function getSandboxMerchantId(): ?string
    {
        return $this->extra_config['sandbox']['merchant_id'] 
            ?? ($this->mode === 'sandbox' ? $this->merchant_id : null);
    }

    public function getLiveKey(): ?string
    {
        return $this->extra_config['live']['api_key'] 
            ?? ($this->mode === 'live' ? $this->api_key : null);
    }

    public function getLiveSecret(): ?string
    {
        return $this->extra_config['live']['api_secret'] 
            ?? ($this->mode === 'live' ? $this->api_secret : null);
    }

    public function getLiveMerchantId(): ?string
    {
        return $this->extra_config['live']['merchant_id'] 
            ?? ($this->mode === 'live' ? $this->merchant_id : null);
    }

    public function getEffectiveApiKeyAttribute(): ?string
    {
        if ($this->mode === 'live') {
            return $this->getLiveKey() ?: $this->api_key;
        }
        return $this->getSandboxKey() ?: $this->api_key;
    }

    public function getEffectiveApiSecretAttribute(): ?string
    {
        if ($this->mode === 'live') {
            return $this->getLiveSecret() ?: $this->api_secret;
        }
        return $this->getSandboxSecret() ?: $this->api_secret;
    }

    public function getEffectiveMerchantIdAttribute(): ?string
    {
        if ($this->mode === 'live') {
            return $this->getLiveMerchantId() ?: $this->merchant_id;
        }
        return $this->getSandboxMerchantId() ?: $this->merchant_id;
    }

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
