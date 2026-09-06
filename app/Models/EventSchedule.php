<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'date',
        'start_time',
        'capacity',
        'is_active',
        'waitlist_closed',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_active' => 'boolean',
        'waitlist_closed' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Mesas propias de esta fecha puntual (ver migración que movió
     * event_tables de pertenecer al evento a pertenecer a la fecha, para
     * poder tener capacidades distintas por fecha).
     */
    public function tables(): HasMany
    {
        return $this->hasMany(EventTable::class, 'event_schedule_id')->orderBy('table_number');
    }

    public function waitlistEntries(): HasMany
    {
        return $this->hasMany(FermentoWaitlistEntry::class, 'event_schedule_id');
    }

    /**
     * Cuántos cupos quedan libres, contando solo reservas no canceladas.
     */
    public function getAvailableSpotsAttribute(): int
    {
        // Las reservas de prueba (is_test, ver ReservationController::store)
        // no cuentan para el aforo — no deben quitarle cupo a nadie más.
        $taken = $this->reservations()->where('status', '!=', 'cancelled')->where('is_test', false)->count();

        return max(0, $this->capacity - $taken);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_spots <= 0;
    }

    /**
     * Mesas de esta fecha con su disponibilidad. Reservar la Mesa 3 para el
     * viernes no toca su disponibilidad el domingo, porque cada mesa
     * pertenece a una sola fecha (event_schedule_id).
     *
     * La mesa social no se marca ocupada por una sola reserva: se llena
     * sumando el party_size de todas las reservas activas contra ella hasta
     * llegar a su capacity_max — expone occupied_seats para que el front
     * pueda mostrar "ya hay X comensales confirmados" antes de reservar.
     */
    public function tablesWithAvailability()
    {
        $activeReservations = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where('is_test', false)
            ->whereNotNull('event_table_id')
            ->get(['event_table_id', 'party_size']);

        $takenTableIds = $activeReservations->pluck('event_table_id')->unique();
        $occupiedByTable = $activeReservations->groupBy('event_table_id')
            ->map(fn ($rows) => $rows->sum('party_size'));

        // Una mesa con is_active=false (ver ReservationAdminController::
        // updateTableAvailability) queda completamente afuera del flujo
        // público — ni siquiera se muestra como ocupada, para casos como el
        // 6 de sept. donde por logística solo se habilitó la mesa
        // comunitaria. El panel admin (ReservationAdminController::tables())
        // sigue mostrando todas las mesas, activas o no, para poder
        // reactivarlas.
        return $this->tables->where('is_active', true)->values()->map(function (EventTable $table) use ($takenTableIds, $occupiedByTable) {
            $occupiedSeats = (int) ($occupiedByTable->get($table->id) ?? 0);

            return [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity_min' => $table->capacity_min,
                'capacity_max' => $table->capacity_max,
                'is_social' => $table->is_social,
                'occupied_seats' => $table->is_social ? $occupiedSeats : null,
                'is_taken' => $table->is_social
                    ? $occupiedSeats >= $table->capacity_max
                    : $takenTableIds->contains($table->id),
            ];
        });
    }

    /**
     * ¿Queda alguna mesa (exclusiva libre, o social con cupo) para esta
     * fecha? Distinto de is_full (que compara contra la capacidad agregada
     * del turno y no sirve una vez que la mesa social permite que varias
     * reservas compartan una sola fila de event_tables). Solo tiene sentido
     * para eventos con mesas propias (Fermento) — si el evento no usa mesas
     * (Íntimo), esto siempre da false y no debe consultarse.
     */
    public function getHasFreeTableAttribute(): bool
    {
        $tables = $this->tablesWithAvailability();

        return $tables->isNotEmpty() && $tables->contains(fn (array $table) => ! $table['is_taken']);
    }
}
