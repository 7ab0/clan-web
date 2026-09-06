<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationChange extends Model
{
    public $timestamps = false;

    protected $fillable = ['reservation_id', 'field', 'old_value', 'new_value'];

    protected $casts = ['created_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (ReservationChange $change) {
            $change->created_at = $change->created_at ?? now();
        });
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
