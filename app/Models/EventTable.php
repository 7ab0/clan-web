<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event_schedule_id',
        'table_number',
        'capacity_min',
        'capacity_max',
        'is_social',
    ];

    protected $casts = [
        'is_social' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * ¿Esta mesa admite un grupo más de $partySize personas? Las mesas
     * exclusivas (is_social = false) solo admiten una reserva activa a la
     * vez. La mesa social admite varios grupos por separado, sumando su
     * party_size, mientras no se pase de capacity_max.
     *
     * $excludeReservationId se usa al editar una reserva ya existente, para
     * no contarla contra sí misma al revalidar su propio cupo.
     */
    public function hasCapacityFor(int $partySize, ?int $excludeReservationId = null): bool
    {
        $query = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where('is_test', false);

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        if ($this->is_social) {
            $occupied = (int) (clone $query)->sum('party_size');

            return $occupied + $partySize <= $this->capacity_max;
        }

        return ! (clone $query)->exists();
    }
}
