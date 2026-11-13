<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SafeEncryptedGatewayExtraConfigCast implements CastsAttributes
{
    private const SECRET_FIELDS = ['api_key', 'api_secret'];

    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        $decoded = is_array($value) ? $value : json_decode((string) $value, true);
        if (!is_array($decoded)) {
            return [];
        }

        foreach (['sandbox', 'live'] as $mode) {
            if (!isset($decoded[$mode]) || !is_array($decoded[$mode])) {
                continue;
            }
            foreach (self::SECRET_FIELDS as $field) {
                if (isset($decoded[$mode][$field]) && is_string($decoded[$mode][$field]) && $decoded[$mode][$field] !== '') {
                    try {
                        $decoded[$mode][$field] = Crypt::decryptString($decoded[$mode][$field]);
                    } catch (DecryptException) {
                        // Preserve legacy plaintext entry cleanly
                    }
                }
            }
        }

        return $decoded;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $data = is_array($value) ? $value : json_decode((string) $value, true);
        if (!is_array($data)) {
            return json_encode([]);
        }

        foreach (['sandbox', 'live'] as $mode) {
            if (!isset($data[$mode]) || !is_array($data[$mode])) {
                continue;
            }
            foreach (self::SECRET_FIELDS as $field) {
                if (isset($data[$mode][$field]) && is_string($data[$mode][$field]) && $data[$mode][$field] !== '') {
                    $data[$mode][$field] = Crypt::encryptString($data[$mode][$field]);
                }
            }
        }

        return json_encode($data);
    }
}

