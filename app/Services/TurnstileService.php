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
     * Checks database AppSetting first, then falls back to config/services.php (.env).
     */
    public function isEnabled(): bool
    {
        // 1. Check database setting first
        $setting = AppSetting::getSettings();
        if ($setting && $setting->turnstile_enabled) {
            return !empty($setting->turnstile_site_key) && !empty($setting->turnstile_secret_key);
        }

        // 2. Check config / .env fallback
        $siteKey = config('services.turnstile.site_key');
        $secretKey = config('services.turnstile.secret_key');
        if (!empty($siteKey) && !empty($secretKey)) {
            return (bool) config('services.turnstile.enabled', true);
        }

        return false;
    }

    /**
     * Get the public site key if enabled.
     */
    public function getSiteKey(): ?string
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $setting = AppSetting::getSettings();
        if ($setting && $setting->turnstile_enabled && !empty($setting->turnstile_site_key)) {
            return trim($setting->turnstile_site_key);
        }

        return trim((string) config('services.turnstile.site_key'));
    }

    /**
     * Get the private secret key if configured.
     */
    public function getSecretKey(): ?string
    {
        $setting = AppSetting::getSettings();
        if ($setting && $setting->turnstile_enabled && !empty($setting->turnstile_secret_key)) {
            return trim($setting->turnstile_secret_key);
        }

        $configSecret = config('services.turnstile.secret_key');
        return !empty($configSecret) ? trim((string) $configSecret) : null;
    }

    /**
     * Verify the Turnstile response token from client.
     * Makes canonical POST request to Cloudflare Turnstile siteverify API.
     *
     * @param string|null $token
     * @param string|null $ip
     * @param string|null $expectedAction (Optional action name e.g. 'contact', 'signup', 'login')
     * @return bool
     */
    public function verify(?string $token, ?string $ip = null, ?string $expectedAction = null): bool
    {
        // 1. If Turnstile is disabled or keys are unconfigured, pass automatically (Fallback mode)
        if (!$this->isEnabled()) {
            return true;
        }

        // 2. If enabled but client sent no token at all
        if (empty($token) || strlen(trim($token)) === 0) {
            Log::warning('Cloudflare Turnstile token missing in submission', ['ip' => $ip]);
            return false;
        }

        $secret = $this->getSecretKey();
        if (empty($secret)) {
            Log::warning('Cloudflare Turnstile secret key missing, bypassing');
            return true;
        }

        try {
            $payload = [
                'secret' => $secret,
                'response' => trim($token),
            ];

            if (!empty($ip)) {
                $payload['remoteip'] = $ip;
            }

            // Execute canonical server-side siteverify POST call to Cloudflare
            $response = Http::asForm()
                ->timeout(8)
                ->post(self::VERIFY_URL, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                if (!empty($data['success'])) {
                    // Check action if provided and returned by Cloudflare
                    if ($expectedAction && isset($data['action']) && $data['action'] !== $expectedAction) {
                        Log::warning('Cloudflare Turnstile action mismatch:', [
                            'expected' => $expectedAction,
                            'received' => $data['action'] ?? null,
                            'ip' => $ip,
                        ]);
                        return false;
                    }

                    Log::info('Cloudflare Turnstile siteverify success', [
                        'hostname' => $data['hostname'] ?? null,
                        'action' => $data['action'] ?? null,
                    ]);
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
                'body' => $response->body(),
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
