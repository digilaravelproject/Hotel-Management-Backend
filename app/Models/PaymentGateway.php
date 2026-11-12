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

    protected $hidden = [
        'api_key',
        'api_secret',
        'extra_config',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'extra_config' => \App\Casts\SafeEncryptedGatewayExtraConfigCast::class,
        'api_key' => \App\Casts\SafeEncryptedStringCast::class,
        'api_secret' => \App\Casts\SafeEncryptedStringCast::class,
    ];

    /**
     * Helper getters for mode-specific credentials.
     */
    public function getSandboxKey(): ?string
    {
        $extra = (array) ($this->extra_config ?? []);
        if (isset($extra['sandbox']) && is_array($extra['sandbox']) && array_key_exists('api_key', $extra['sandbox'])) {
            return $extra['sandbox']['api_key'];
        }
        return $this->mode === 'sandbox' ? $this->api_key : null;
    }

    public function getSandboxSecret(): ?string
    {
        $extra = (array) ($this->extra_config ?? []);
        if (isset($extra['sandbox']) && is_array($extra['sandbox']) && array_key_exists('api_secret', $extra['sandbox'])) {
            return $extra['sandbox']['api_secret'];
        }
        return $this->mode === 'sandbox' ? $this->api_secret : null;
    }

    public function getSandboxMerchantId(): ?string
    {
        $extra = (array) ($this->extra_config ?? []);
        if (isset($extra['sandbox']) && is_array($extra['sandbox']) && array_key_exists('merchant_id', $extra['sandbox'])) {
            return $extra['sandbox']['merchant_id'];
        }
        return $this->mode === 'sandbox' ? $this->merchant_id : null;
    }

    public function getLiveKey(): ?string
    {
        $extra = (array) ($this->extra_config ?? []);
        if (isset($extra['live']) && is_array($extra['live']) && array_key_exists('api_key', $extra['live'])) {
            return $extra['live']['api_key'];
        }
        return $this->mode === 'live' ? $this->api_key : null;
    }

    public function getLiveSecret(): ?string
    {
        $extra = (array) ($this->extra_config ?? []);
        if (isset($extra['live']) && is_array($extra['live']) && array_key_exists('api_secret', $extra['live'])) {
            return $extra['live']['api_secret'];
        }
        return $this->mode === 'live' ? $this->api_secret : null;
    }

    public function getLiveMerchantId(): ?string
    {
        $extra = (array) ($this->extra_config ?? []);
        if (isset($extra['live']) && is_array($extra['live']) && array_key_exists('merchant_id', $extra['live'])) {
            return $extra['live']['merchant_id'];
        }
        return $this->mode === 'live' ? $this->merchant_id : null;
    }

    public function getEffectiveApiKeyAttribute(): ?string
    {
        $hasExtra = is_array($this->extra_config) && !empty($this->extra_config);
        if ($this->mode === 'live') {
            return $hasExtra ? $this->getLiveKey() : $this->api_key;
        }
        return $hasExtra ? $this->getSandboxKey() : $this->api_key;
    }

    public function getEffectiveApiSecretAttribute(): ?string
    {
        $hasExtra = is_array($this->extra_config) && !empty($this->extra_config);
        if ($this->mode === 'live') {
            return $hasExtra ? $this->getLiveSecret() : $this->api_secret;
        }
        return $hasExtra ? $this->getSandboxSecret() : $this->api_secret;
    }

    public function getEffectiveMerchantIdAttribute(): ?string
    {
        $hasExtra = is_array($this->extra_config) && !empty($this->extra_config);
        if ($this->mode === 'live') {
            return $hasExtra ? $this->getLiveMerchantId() : $this->merchant_id;
        }
        return $hasExtra ? $this->getSandboxMerchantId() : $this->merchant_id;
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
