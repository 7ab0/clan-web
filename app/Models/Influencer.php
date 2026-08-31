<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Influencer invitado al pre-cóctel de Fermento, con link personalizado por
 * token. Mismo mecanismo de token de 8 caracteres que FermentoGuest, pero en
 * tabla propia: el pre-cóctel es un evento aparte de las reservas de mesa de
 * Fermento (mismo criterio que ya separa Guest/IntimoGuest/FermentoGuest).
 */
class Influencer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'instagram_handle',
        'tiktok_handle',
        'phone',
        'followers_count',
        'token',
        'opened_at',
        'status',
        'confirmed_at',
        'attended_at',
        'notes',
        'is_test',
    ];

    protected $casts = [
        'followers_count' => 'integer',
        'opened_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'attended_at' => 'datetime',
        'is_test' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Influencer $influencer) {
            if (empty($influencer->token)) {
                $influencer->token = static::generateToken();
            }
        });
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::lower(Str::random(8));
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(InfluencerPost::class);
    }

    /**
     * Primer nombre, para el saludo del preloader de Fermento cuando el
     * token es de un influencer (mismo patrón que FermentoGuest).
     */
    public function getFirstNameAttribute(): string
    {
        return trim(explode(' ', trim($this->name))[0]);
    }
}
