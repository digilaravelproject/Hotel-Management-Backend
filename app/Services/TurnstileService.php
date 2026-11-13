<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    public const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Check if Cloudflare Turnstile is actively enabled and configured.
     */
    public function isEnabled(): bool
    {
        $setting = AppSetting::getSettings();
        if (!$setting || !$setting->turnstile_enabled) {
            return false;
        }

        return !empty($setting->turnstile_site_key) && !empty($setting->turnstile_secret_key);
    }

    /**
     * Get the public site key if enabled.
     */
    public function getSiteKey(): ?string
    {
        if (!$this->isEnabled()) {
            return null;
        }

        return AppSetting::getSettings()->turnstile_site_key;
    }

    /**
     * Get the private secret key if configured.
     */
    public function getSecretKey(): ?string
    {
        return AppSetting::getSettings()?->turnstile_secret_key;
    }

    /**
     * Verify the Turnstile response token from client.
     *
     * @param string|null $token
     * @param string|null $ip
     * @return bool
     */
    public function verify(?string $token, ?string $ip = null): bool
    {
        // 1. If Turnstile is disabled or keys are unconfigured, pass automatically (Fallback mode)
        if (!$this->isEnabled()) {
            return true;
        }

        // 2. If enabled but client sent no token at all
        if (empty($token)) {
            return false;
        }

        $secret = $this->getSecretKey();
        if (empty($secret)) {
            return true;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(self::VERIFY_URL, array_filter([
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $ip,
                ]));

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['success'])) {
                    return true;
                }

                Log::warning('Cloudflare Turnstile verification rejected token:', [
                    'ip' => $ip,
                    'error_codes' => $data['error-codes'] ?? [],
                ]);
                return false;
            }

            // If Cloudflare returns a 5xx server error, fail-open (smart fallback) so visitors aren't blocked
            Log::warning('Cloudflare Turnstile server returned non-200 status, failing open:', [
                'status' => $response->status(),
            ]);
            return true;

        } catch (\Throwable $e) {
            // Network timeout / exception -> smart fallback
            Log::warning('Cloudflare Turnstile connection exception, failing open:', [
                'message' => $e->getMessage(),
            ]);
            return true;
        }
    }
}

