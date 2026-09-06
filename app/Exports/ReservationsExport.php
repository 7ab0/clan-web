<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Mismas columnas/datos que el CSV plano anterior (Reservation::csvHeaders()/
 * toCsvRow()) — esto solo mejora la presentación: encabezado en negrita,
 * columnas autoajustadas al contenido y primera fila congelada al hacer
 * scroll. Reutilizado por ReservationAdminController::export() (todas las
 * reservas) y ReservationReviewController::export() (solo confirmadas).
 */
class ReservationsExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithStyles
{
    public function __construct(private readonly Collection $reservations)
    {
    }

    public function collection(): Collection
    {
        return $this->reservations->map->toCsvRow();
    }

    public function headings(): array
    {
        return Reservation::csvHeaders();
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
