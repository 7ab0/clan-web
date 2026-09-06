<?php

namespace App\Models;

use Carbon\Carbon;
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
        'event_schedule_id',
        'name',
        'phone',
        'token',
        'opened_at',
        'whatsapp_sent_at',
        'interest_confirmed_at',
        'invite_sent_at',
        'is_test',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'whatsapp_sent_at' => 'datetime',
        'interest_confirmed_at' => 'datetime',
        'invite_sent_at' => 'datetime',
        'is_test' => 'boolean',
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

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
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

        $blocks = [
            'Hola, *' . $this->first_name . "* 👋",
            '*MOLTO* × *FORNO* estamos preparando algo *especial.*',
            '¿Te gustaría conocer los detalles? ✨',
        ];

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode(implode("\n\n", $blocks));
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

        $blocks = [
            '*' . $this->first_name . '*, llegó el momento.',
            "*MOLTO* × *FORNO* presentan *FERMENTO*\nTransmutación de la masa madre.",
            'Una *experiencia* a cuatro manos donde el tiempo, el *fuego* y la *creatividad* se encuentran.',
            "Queremos que seas parte de esta noche.\n✨ *{$this->dateLabel()}* · *{$this->timeLabel()}*",
            "📍 Pasaje Violín 101 F, San Lázaro\nPlaza Campo Redondo – Arequipa",
            "Tu *invitación* es *personal*:\n👉 " . route('fermento', $this->token),
        ];

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode(implode("\n\n", $blocks));
    }

    /**
     * Fecha en español para el mensaje de WhatsApp y la imagen de
     * invitación — usa la fecha asignada al invitado (ver
     * ReservationAdminController::storeGuest/updateGuest); si todavía no
     * tiene una asignada, cae a una frase genérica en vez de mentir una
     * fecha puntual.
     */
    public function dateLabel(): string
    {
        $date = $this->schedule?->date;

        if (! $date instanceof Carbon) {
            return 'Viernes 4 o domingo 6 de septiembre';
        }

        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return $dias[(int) $date->format('w')] . ' ' . (int) $date->format('j') . ' de septiembre';
    }

    /**
     * Hora para el mensaje de WhatsApp. Hubo un cambio de logística a 6:00
     * p.m. para el 6 de septiembre que se revirtió el mismo día — vuelve a
     * ser fija en 7:00 p.m. para las 3 fechas.
     */
    public function timeLabel(): string
    {
        return '7:00 p. m.';
    }

    /**
     * Misma hora que timeLabel(), en el formato usado por la imagen de
     * invitación (sin punto ni espacio antes de la M).
     */
    public function timeLabelForImage(): string
    {
        return '7:00 PM';
    }
}
