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
        'interest_confirmed_at',
        'invite_sent_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
        'interest_confirmed_at' => 'datetime',
        'invite_sent_at' => 'datetime',
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

    /**
     * Link de WhatsApp para el mensaje 1 (intriga, sin link personalizado
     * todavía) — null si el invitado no tiene teléfono cargado.
     */
    public function waLinkIntriga(): ?string
    {
        $phone = Customer::normalizePhone($this->phone);

        if ($phone === null) {
            return null;
        }

        $lines = [
            'Hola, *' . $this->first_name . "* 👋",
            '*MOLTO* × *FORNO* estamos preparando algo especial.',
            '¿Te *gustaría* conocer los detalles? ✨',
        ];

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode(implode("\n", $lines));
    }

    /**
     * Link de WhatsApp para el mensaje 2 (invitación completa, con su link
     * personalizado) — null si el invitado no tiene teléfono cargado.
     */
    public function waLinkInvitacion(): ?string
    {
        $phone = Customer::normalizePhone($this->phone);

        if ($phone === null) {
            return null;
        }

        $lines = [
            '*' . $this->first_name . '*, llegó el momento.',
            '*MOLTO* × *FORNO* presentan *FERMENTO*',
            'Transmutación de la masa madre.',
            'Una experiencia a cuatro manos donde el tiempo, el fuego y la creatividad se encuentran.',
            'Queremos que seas parte de esta noche.',
            '✨ Viernes 4 o sábado 5 de septiembre · 7:00 p. m.',
            '📍 Pasaje Violín 101 F, San Lázaro',
            'Plaza Campo Redondo – Arequipa',
            'Tu invitación es personal:',
            '👉 ' . route('fermento', $this->token),
        ];

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode(implode("\n", $lines));
    }
}
