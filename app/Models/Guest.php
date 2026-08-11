<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Guest $guest) {
            if (empty($guest->code)) {
                $guest->code = static::generateCode();
            }
        });
    }

    public static function generateCode(): string
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    protected $fillable = [
        'code',
        'name',
        'profession',
        'compliment',
        'email',
        'phone',
        'status',
        'pre_invitation_sent',
        'invitation_sent',
        'allowed_companions',
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
            'pre_invitation_sent' => 'boolean',
            'invitation_sent' => 'boolean',
            'allowed_companions' => 'integer',
            'confirmed_at' => 'datetime',
        ];
    }
}
