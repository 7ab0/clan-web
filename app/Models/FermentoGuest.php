<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Invitado de Fermento, con link personalizado por token.
 *
 * Calcado de IntimoGuest, pero en tabla propia (fermento_guests): se decidió
 * mantener cada evento con su propia tabla de invitados en vez de reusar
 * IntimoGuest (mismo criterio que ya separa Guest de ShowClinic de IntimoGuest).
 */
class FermentoGuest extends Model
{
    use HasFactory;

    protected $table = 'fermento_guests';

    protected $fillable = [
        'event_id',
        'name',
        'phone',
        'token',
        'opened_at',
        'whatsapp_sent_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (FermentoGuest $guest) {
            if (empty($guest->token)) {
                $guest->token = static::generateToken();
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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Primer nombre, para un saludo más cercano ("Hola, Mauricio" en vez del nombre completo).
     */
    public function getFirstNameAttribute(): string
    {
        return trim(explode(' ', trim($this->name))[0]);
    }
}
