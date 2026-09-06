<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Manifestación de interés cuando una fecha de Fermento se quedó sin mesas
 * (viernes 4 o domingo 6 — el sábado 5 no tiene lista de espera, está
 * cerrado del todo). No asigna mesa ni confirma nada: el encargado la revisa
 * a mano desde /reservas/admin/lista-espera y contacta si se libera un cupo.
 */
class FermentoWaitlistEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_schedule_id',
        'name',
        'phone',
        'party_size',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
    }
}
