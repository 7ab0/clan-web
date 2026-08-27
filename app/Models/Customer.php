<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'brands',
        'frequency',
        'vip',
        'birth_month',
        'birth_day',
        'notes',
    ];

    protected $casts = [
        'brands' => 'array',
        'vip' => 'boolean',
    ];

    /**
     * Normaliza un teléfono a formato "51XXXXXXXXX" para usarlo como clave
     * de deduplicación. Los celulares peruanos locales tienen 9 dígitos
     * (como se piden en los formularios de reserva); si el cliente ya
     * escribió el prefijo de país, se respeta tal cual.
     */
    public static function normalizePhone(?string $raw): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $raw);

        if ($digits === '') {
            return null;
        }

        return strlen($digits) === 9 ? '51' . $digits : $digits;
    }
}
