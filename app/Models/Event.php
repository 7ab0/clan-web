<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'project',
        'name',
        'slug',
        'tagline',
        'description',
        'courses',
        'party_size',
        'price',
        'currency',
        'video_url',
        'cover_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(EventSchedule::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(EventTable::class)->orderBy('table_number');
    }

    /**
     * Turnos futuros y activos con cupo disponible, ordenados por fecha/hora.
     */
    public function upcomingSchedules()
    {
        return $this->schedules()
            ->where('is_active', true)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->withCount(['reservations as reserved_count' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }])
            ->get()
            ->filter(fn ($schedule) => $schedule->reserved_count < $schedule->capacity)
            ->values();
    }
}
