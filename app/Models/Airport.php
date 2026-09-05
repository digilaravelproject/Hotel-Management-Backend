<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $table = 'airports';

    protected $fillable = [
        'name',
        'iata_code',
        'icao_code',
        'city',
        'country',
        'timezone',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function setIataCodeAttribute($value)
    {
        $this->attributes['iata_code'] = strtoupper(trim($value));
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->name} ({$this->iata_code})";
    }

    public function hotelAdminsPrimary()
    {
        return $this->hasMany(HotelAdmin::class, 'primary_airport_id');
    }

    public function hotelAdminsSecondary()
    {
        return $this->hasMany(HotelAdmin::class, 'secondary_airport_id');
    }
}
