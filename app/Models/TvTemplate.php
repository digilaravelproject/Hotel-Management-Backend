<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TvTemplate extends Model
{
    protected $table = 'tv_templates';

    protected $fillable = [
        'theme_id',
        'theme_name',
        'version',
        'file_path',
        'preview_image',
        'is_active',
    ];

    protected $casts = [
        'theme_id' => 'integer',
        'is_active' => 'boolean',
    ];
}
