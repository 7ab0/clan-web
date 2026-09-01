<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Controller
{
    /**
     * CSV descargable a partir de encabezados + filas ya armadas (ver
     * Reservation::csvHeaders()/toCsvRow()). BOM UTF-8 al inicio para que
     * Excel detecte tildes/ñ sin pedir "importar" el archivo a mano.
     */
    protected function csvDownload(string $filename, array $headers, Collection $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
