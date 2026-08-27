<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'event_id',
        'event_schedule_id',
        'event_table_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'party_size',
        'relationship_type',
        'notes',
        'total_amount',
        'status',
        'is_test',
    ];

    protected $casts = [
        'is_test' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Reservation $reservation) {
            if (empty($reservation->code)) {
                $slug = optional(Event::find($reservation->event_id))->slug;
                $prefix = match ($slug) {
                    'fermento' => 'FER',
                    default => 'INT',
                };
                $reservation->code = static::generateCode($prefix);
            }
        });
    }

    public static function generateCode(string $prefix = 'INT'): string
    {
        do {
            $code = $prefix . '-' . strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(EventTable::class, 'event_table_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
