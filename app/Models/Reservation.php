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
        'customer_name',
        'customer_email',
        'customer_phone',
        'party_size',
        'relationship_type',
        'notes',
        'total_amount',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Reservation $reservation) {
            if (empty($reservation->code)) {
                $reservation->code = static::generateCode();
            }
        });
    }

    public static function generateCode(): string
    {
        do {
            $code = 'INT-' . strtoupper(Str::random(6));
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

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
