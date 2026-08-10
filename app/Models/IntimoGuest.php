<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Invitado de un evento tipo Íntimo, con link personalizado por token.
 *
 * Se llama IntimoGuest (tabla intimo_guests) en vez de Guest/guests para no
 * chocar con el modelo Guest existente, que es el RSVP de ShowClinic —
 * mismo nombre obvio, propósito y esquema totalmente distintos.
 */
class IntimoGuest extends Model
{
    use HasFactory;

    protected $table = 'intimo_guests';

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
        static::creating(function (IntimoGuest $guest) {
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
     * Primer nombre, para un saludo más cercano ("Kamisaraki, Mauricio" en vez del nombre completo).
     */
    public function getFirstNameAttribute(): string
    {
        return trim(explode(' ', trim($this->name))[0]);
    }
}
