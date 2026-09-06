<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function changes(): HasMany
    {
        return $this->hasMany(ReservationChange::class)->orderBy('created_at', 'desc');
    }

    /**
     * Encabezados de la exportación CSV — deben mantenerse en el mismo
     * orden que toCsvRow() (ver ReservationAdminController::export() y
     * ReservationReviewController::export()).
     */
    public static function csvHeaders(): array
    {
        return ['Código', 'Evento', 'Fecha', 'Mesa', 'Nombre', 'Teléfono', 'Correo', 'Personas', 'Estado', 'Seña', 'Notas', 'Creada'];
    }

    /**
     * Fila de la exportación CSV. Asume event/schedule/table/payment ya
     * cargados (eager loading) — llamarlo sobre una colección sin
     * with(...) dispara consultas N+1.
     */
    public function toCsvRow(): array
    {
        return [
            $this->code,
            $this->event->name,
            $this->schedule
                ? $this->schedule->date->format('d/m/Y') . ' ' . Str::of($this->schedule->start_time)->substr(0, 5)
                : '-',
            // $this->table (sin getAttribute) choca con la propiedad interna
            // Model::$table (nombre de tabla SQL) al llamarse desde dentro de
            // la propia clase — fuera de ella (blades/controllers) el accessor
            // mágico de Eloquent sí resuelve bien la relación table().
            $this->getAttribute('table') ? '#' . $this->getAttribute('table')->table_number : '-',
            $this->customer_name,
            $this->customer_phone ?: '-',
            $this->customer_email,
            $this->party_size,
            static::statusLabel($this->status),
            $this->payment ? number_format((float) $this->payment->amount, 2, '.', '') : '-',
            $this->notes ?: '',
            $this->created_at->format('d/m/Y H:i'),
        ];
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'completed' => 'Completada',
            default => $status,
        };
    }
}
