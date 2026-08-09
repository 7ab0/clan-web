<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'status',
        'plus_one',
        'companion_name',
        'preferences',
        'notes',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'plus_one' => 'boolean',
            'confirmed_at' => 'datetime',
        ];
    }
}
