<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvTemplate extends Model
{
    protected $table = 'tv_templates';

    protected $fillable = [
        'version',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
