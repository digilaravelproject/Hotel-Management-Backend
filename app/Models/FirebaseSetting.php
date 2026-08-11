<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseSetting extends Model
{
    protected $table = 'firebase_settings';

    protected $fillable = [
        'project_id',
        'service_account_json',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Mutator to encrypt service_account_json before saving.
     */
    public function setServiceAccountJsonAttribute($value): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $this->attributes['service_account_json'] = $value ? \Illuminate\Support\Facades\Crypt::encryptString($value) : null;
    }

    /**
     * Accessor to decrypt service_account_json upon reading.
     */
    public function getServiceAccountJsonAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($value);
        } catch (\Exception $e) {
            // Fallback for unencrypted legacy content
            return $value;
        }
    }

    /**
     * Get active Firebase configuration record.
     */
    public static function getActiveSetting(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }
}
