<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * /reservas — landing pública de reservas de Clan (setiembre 2026).
 *
 * Formulario liviano y descartable a propósito (el sitio completo de
 * clan-web se rehace más adelante). La Comanda (Next.js/Prisma/Postgres) es
 * la fuente de verdad de las reservas de Clan — este controller solo hace
 * de proxy server-to-server hacia /api/public/reservas, nunca expone ese
 * endpoint directo al navegador del cliente (así no hace falta CORS).
 *
 * Reglas de negocio (rescatadas de Fermento, ver CLAUDE.md de La Comanda):
 * candado mesa+fecha+hora y seña mínima S/30/persona se validan del lado de
 * La Comanda — acá solo se valida forma de los datos antes de reenviarlos.
 */
class ReservationClanController extends Controller
{
    private const OPEN_MINUTES = 13 * 60 + 30; // 13:30
    private const LAST_SEATING_MINUTES = 21 * 60 + 30; // 21:30 (ver clanLastSeatingMinutes() en La Comanda)
    private const SLOT_STEP_MINUTES = 30;

    public function show()
    {
        return view('home.reservas-clan', [
            'today' => Carbon::today()->toDateString(),
            'timeSlots' => $this->timeSlots(),
        ]);
    }

    /** Proxy AJAX de disponibilidad — GET /reservas/disponibilidad?fecha=&hora=&pax= */
    public function disponibilidad(Request $request): JsonResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date_format:Y-m-d'],
            'hora' => ['required', 'date_format:H:i'],
            'pax' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        return $this->forward('get', '/api/public/reservas', $data);
    }

    /** Crea la reserva — POST /reservas */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date_format:Y-m-d'],
            'hora' => ['required', 'date_format:H:i'],
            'partySize' => ['required', 'integer', 'min:1', 'max:50'],
            'tableId' => ['nullable', 'string'],
            'customerName' => ['required', 'string', 'max:120'],
            'customerPhone' => ['required', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:500'],
            'depositAmount' => ['required', 'numeric', 'min:0'],
        ]);

        return $this->forward('post', '/api/public/reservas', $data);
    }

    /**
     * Reenvía la consulta a La Comanda y devuelve tal cual su respuesta
     * (mismo status HTTP, mismo cuerpo) — la validación real de negocio
     * (horario, candado mesa+fecha+hora, seña mínima) vive ahí, no acá.
     */
    private function forward(string $method, string $path, array $data): JsonResponse
    {
        $baseUrl = config('services.la_comanda.api_base_url');

        try {
            $response = $method === 'get'
                ? Http::baseUrl($baseUrl)->get($path, $data)
                : Http::baseUrl($baseUrl)->asJson()->post($path, $data);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json(
                ['error' => 'No pudimos conectar con el sistema de reservas. Intenta de nuevo en un momento.'],
                502
            );
        }

        return response()->json($response->json() ?? [], $response->status());
    }

    /** Franjas de 30 min dentro del horario real de Clan (13:30 a 21:30, último ingreso). */
    private function timeSlots(): array
    {
        $slots = [];
        for ($m = self::OPEN_MINUTES; $m <= self::LAST_SEATING_MINUTES; $m += self::SLOT_STEP_MINUTES) {
            $value = sprintf('%02d:%02d', intdiv($m, 60), $m % 60);
            $slots[] = ['value' => $value, 'label' => $this->formatLabel($m)];
        }
        return $slots;
    }

    private function formatLabel(int $minutesOfDay): string
    {
        $h = intdiv($minutesOfDay, 60);
        $min = $minutesOfDay % 60;
        $period = $h >= 12 ? 'pm' : 'am';
        $h12 = $h % 12 === 0 ? 12 : $h % 12;
        return sprintf('%d:%02d %s', $h12, $min, $period);
    }
}
