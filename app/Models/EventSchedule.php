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
        $taken = $this->reservations()->where('status', '!=', 'cancelled')->count();

        return max(0, $this->capacity - $taken);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->available_spots <= 0;
    }
}
