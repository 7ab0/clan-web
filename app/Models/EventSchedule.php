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
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_active' => 'boolean',
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
     * Mesas del evento con su disponibilidad para esta fecha puntual. Reservar
     * la Mesa 3 para el viernes no toca su disponibilidad el sábado, porque
     * cada fila de event_schedules es una fecha independiente.
     */
    public function tablesWithAvailability()
    {
        $takenTableIds = $this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where('is_test', false)
            ->whereNotNull('event_table_id')
            ->pluck('event_table_id');

        return $this->event->tables->map(fn (EventTable $table) => [
            'id' => $table->id,
            'table_number' => $table->table_number,
            'capacity_min' => $table->capacity_min,
            'capacity_max' => $table->capacity_max,
            'is_taken' => $takenTableIds->contains($table->id),
        ]);
    }
}
