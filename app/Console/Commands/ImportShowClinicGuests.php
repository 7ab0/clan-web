<?php

namespace App\Console\Commands;

use App\Models\Guest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportShowClinicGuests extends Command
{
    /**
     * php artisan showclinic:guests-import ruta/al/archivo.csv
     *
     * El CSV debe tener encabezado: nombre,celular,acompanantes_permitidos
     * Cada invitado recibe un código único de 6 caracteres alfanuméricos
     * (Guest::generateCode()) si no existe ya uno con el mismo nombre+celular.
     */
    protected $signature = 'showclinic:guests-import {csv : Ruta al archivo CSV}';

    protected $description = 'Importa invitados de ShowClinic desde un CSV (nombre, celular, acompanantes_permitidos)';

    public function handle(): int
    {
        $path = $this->argument('csv');

        if (! File::exists($path)) {
            $this->error("No encontré el archivo: {$path}");

            return self::FAILURE;
        }

        $rows = array_map('str_getcsv', File::lines($path)->toArray());
        $header = array_map(fn ($h) => strtolower(trim($h)), array_shift($rows));

        $nameIndex = array_search('nombre', $header, true);
        $phoneIndex = array_search('celular', $header, true);
        $companionsIndex = array_search('acompanantes_permitidos', $header, true);

        if ($nameIndex === false) {
            $this->error('No encontré la columna "nombre" en el CSV.');

            return self::FAILURE;
        }

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            if (! isset($row[$nameIndex]) || trim($row[$nameIndex]) === '') {
                continue;
            }

            $name = mb_convert_case(mb_strtolower(trim($row[$nameIndex]), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $phone = $phoneIndex !== false && isset($row[$phoneIndex])
                ? preg_replace('/\D+/', '', $row[$phoneIndex])
                : null;
            $allowedCompanions = $companionsIndex !== false && isset($row[$companionsIndex])
                ? (int) trim($row[$companionsIndex])
                : 0;

            $exists = Guest::where('name', $name)->where('phone', $phone)->exists();

            if ($exists) {
                $skipped++;

                continue;
            }

            Guest::create([
                'name' => $name,
                'phone' => $phone ?: null,
                'allowed_companions' => $allowedCompanions,
            ]);

            $imported++;
        }

        $this->info("{$imported} invitados importados.");

        if ($skipped > 0) {
            $this->warn("{$skipped} filas omitidas (ya existía un invitado con el mismo nombre y celular).");
        }

        return self::SUCCESS;
    }
}
