<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\IntimoGuest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportGuests extends Command
{
    /**
     * php artisan intimo:guests-import ruta/al/archivo.csv
     *
     * El CSV debe tener encabezados. Se aceptan variantes en español o inglés:
     * nombre|name  y  telefono|phone|celular
     */
    protected $signature = 'intimo:guests-import {csv : Ruta al archivo CSV} {--event=intimo : Slug del evento} {--base-url= : URL base para armar el link, ej. https://clan.rest}';

    protected $description = 'Importa invitados de Íntimo desde un CSV y genera su link personalizado para el evento indicado';

    public function handle(): int
    {
        $path = $this->argument('csv');

        if (! File::exists($path)) {
            $this->error("No encontré el archivo: {$path}");

            return self::FAILURE;
        }

        $event = Event::where('slug', $this->option('event'))->first();

        if (! $event) {
            $this->error("No existe un evento con slug '{$this->option('event')}'.");

            return self::FAILURE;
        }

        $baseUrl = rtrim($this->option('base-url') ?: config('app.url'), '/');

        $rows = array_map('str_getcsv', File::lines($path)->toArray());
        $header = array_map(fn ($h) => strtolower(trim($h)), array_shift($rows));

        $nameKeys = ['nombre', 'name'];
        $phoneKeys = ['telefono', 'teléfono', 'phone', 'celular', 'whatsapp'];

        $nameIndex = $this->findColumn($header, $nameKeys);
        $phoneIndex = $this->findColumn($header, $phoneKeys);

        if ($nameIndex === null) {
            $this->error('No encontré una columna de nombre (nombre/name) en el CSV.');

            return self::FAILURE;
        }

        $results = [];

        foreach ($rows as $row) {
            if (! isset($row[$nameIndex]) || trim($row[$nameIndex]) === '') {
                continue;
            }

            $name = trim($row[$nameIndex]);
            $phone = $phoneIndex !== null && isset($row[$phoneIndex]) ? trim($row[$phoneIndex]) : null;

            $guest = IntimoGuest::create([
                'event_id' => $event->id,
                'name' => $name,
                'phone' => $phone ?: null,
            ]);

            $results[] = [
                'name' => $guest->name,
                'phone' => $guest->phone,
                'link' => "{$baseUrl}/intimo/{$guest->token}",
            ];
        }

        if (empty($results)) {
            $this->warn('No se importó ningún invitado (revisa que el CSV tenga filas con nombre).');

            return self::SUCCESS;
        }

        $this->table(['Nombre', 'Teléfono', 'Link personalizado'], $results);

        $outputPath = storage_path('app/intimo-guests-links-' . now()->format('Y-m-d-His') . '.csv');
        $csvLines = ["nombre,telefono,link"];
        foreach ($results as $r) {
            $csvLines[] = '"' . str_replace('"', '""', $r['name']) . '","' . $r['phone'] . '","' . $r['link'] . '"';
        }
        File::put($outputPath, implode("\n", $csvLines));

        $this->info(count($results) . ' invitados importados.');
        $this->info("CSV con los links guardado en: {$outputPath}");

        return self::SUCCESS;
    }

    private function findColumn(array $header, array $candidates): ?int
    {
        foreach ($candidates as $candidate) {
            $index = array_search($candidate, $header, true);
            if ($index !== false) {
                return $index;
            }
        }

        return null;
    }
}
