<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OttPlatform extends Model
{
    protected $table = 'ott_platforms';

    protected $fillable = [
        'name',
        'package_name',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
