<?php

namespace App\Services;

use App\Models\FirebaseSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseFcmService
{
    /**
     * Get dynamic Firebase Service Account array (DB first, fallback to storage file/env).
     */
    protected function getServiceAccountConfig(): ?array
    {
        // 1. Check Database first (Super Admin provisioned key)
        $setting = FirebaseSetting::getActiveSetting();
        if ($setting && !empty($setting->service_account_json)) {
            $json = is_array($setting->service_account_json) 
                ? $setting->service_account_json 
                : json_decode($setting->service_account_json, true);
            if (is_array($json) && isset($json['project_id'], $json['private_key'], $json['client_email'])) {
                return $json;
            }
        }

        // 2. Check storage path fallback
        $path = storage_path('app/firebase/service-account.json');
        if (file_exists($path)) {
            $content = file_get_contents($path);
            $json = json_decode($content, true);
            if (is_array($json) && isset($json['project_id'], $json['private_key'], $json['client_email'])) {
                return $json;
            }
        }

        // 3. Check env file path fallback
        $envPath = config('services.firebase.credentials_file');
        if ($envPath && file_exists($envPath)) {
            $content = file_get_contents($envPath);
            $json = json_decode($content, true);
            if (is_array($json) && isset($json['project_id'], $json['private_key'], $json['client_email'])) {
                return $json;
            }
        }

        return null;
    }

    /**
     * Get Google OAuth2 Access Token for FCM HTTP v1 API (Cached for 50 minutes).
     */
    public function getAccessToken(): ?string
    {
        $config = $this->getServiceAccountConfig();
        if (!$config) {
            Log::info('Firebase FCM credentials not configured yet. Skipping token generation.');
            return null;
        }

        $cacheKey = 'firebase_fcm_access_token_' . md5($config['client_email']);

        return Cache::remember($cacheKey, 3000, function () use ($config) {
            $now = time();
            $payload = [
                'iss' => $config['client_email'],
                'sub' => $config['client_email'],
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            ];

            $jwt = $this->generateJwt($payload, $config['private_key']);
            if (!$jwt) {
                return null;
            }

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('Failed to obtain Google OAuth token for FCM', ['body' => $response->body()]);
            return null;
        });
    }

    /**
     * Generate signed JWT token using RS256.
     */
    protected function generateJwt(array $payload, string $privateKey): ?string
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];

        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));

        $signatureInput = $base64UrlHeader . "." . $base64UrlPayload;

        $signature = '';
        $success = openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        if (!$success) {
            Log::error('OpenSSL failed to sign JWT for FCM OAuth');
            return null;
        }

        return $signatureInput . "." . $this->base64UrlEncode($signature);
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Send high-priority FCM Silent Data Push to a specific device token.
     */
    public function sendToToken(string $token, array $dataPayload): bool
    {
        return $this->sendMessage(['token' => $token], $dataPayload);
    }

    /**
     * Send high-priority FCM Silent Data Push to a topic (e.g. hotel_42, all_tvs).
     */
    public function sendToTopic(string $topic, array $dataPayload): bool
    {
        // Clean topic name
        $cleanTopic = preg_replace('/[^a-zA-Z0-9-_.~%]/', '_', $topic);
        return $this->sendMessage(['topic' => $cleanTopic], $dataPayload);
    }

    /**
     * Send raw FCM message to Google FCM HTTP v1 endpoint.
     */
    protected function sendMessage(array $target, array $dataPayload): bool
    {
        $config = $this->getServiceAccountConfig();
        if (!$config) {
            Log::info('Firebase FCM credentials not configured yet. Skipping FCM push.');
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::warning('FCM OAuth access token unavailable.');
            return false;
        }

        $projectId = $config['project_id'];
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // Convert all payload values to string for FCM data payload compliance
        $stringData = [];
        foreach ($dataPayload as $key => $value) {
            $stringData[(string) $key] = is_array($value) ? json_encode($value) : (string) $value;
        }

        $stringData['type'] = 'SYNC_TRIGGER';
        $stringData['timestamp'] = (string) now()->timestamp;

        $body = [
            'message' => array_merge($target, [
                'data' => $stringData,
                'android' => [
                    'priority' => 'HIGH',
                    'ttl' => '0s', // Immediate delivery without buffering
                ],
            ]),
        ];

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $body);

        if ($response->successful()) {
            Log::info('FCM Silent Data Push sent successfully', ['target' => $target, 'data' => $stringData]);
            return true;
        }

        Log::error('Failed to send FCM Data Push', [
            'target' => $target,
            'status' => $response->status(),
            'response' => $response->body(),
        ]);

        return false;
    }
}
