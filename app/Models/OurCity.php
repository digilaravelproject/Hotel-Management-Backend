<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurCity extends Model
{
    use HasFactory;

    protected $table = 'our_cities';

    protected $fillable = [
        'hotel_admin_id',
        'sr_no',
        'title',
        'image',
        'description',
        'attractions',
        'features',
        'status',
    ];

    protected $casts = [
        'sr_no' => 'integer',
        'attractions' => 'array',
        'features' => 'array',
        'status' => 'boolean',
    ];

    /**
     * Ensure attractions always returns a clean array even if MySQL returns a raw JSON string.
     */
    public function getAttractionsAttribute($value): array
    {
        if (is_null($value)) {
            $fallback = $this->attributes['features'] ?? null;
            if ($fallback) {
                return is_string($fallback) ? (json_decode($fallback, true) ?: []) : (array) $fallback;
            }
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }

    /**
     * Ensure attractions is properly normalized before persisting.
     */
    public function setAttractionsAttribute($value): void
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $clean = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$value];
        } else {
            $clean = is_array($value) ? array_values(array_filter($value)) : [];
        }

        $this->attributes['attractions'] = json_encode($clean);
    }

    /**
     * Relationship to the HotelAdmin.
     */
    public function hotelAdmin()
    {
        return $this->belongsTo(HotelAdmin::class, 'hotel_admin_id');
    }
}
