<?php

namespace App\Http\Requests;

use App\Models\EventSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_schedule_id' => ['required', 'exists:event_schedules,id'],
            'event_table_id' => ['nullable', 'exists:event_tables,id'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'party_size' => ['required', 'integer', 'min:1', 'max:20'],
            'relationship_type' => ['nullable', 'string', 'max:60'],
            'notes' => ['nullable', 'string', 'max:500'],
            // Monto de la seña que el cliente elige coordinar por WhatsApp.
            // Si no se manda (ej. Íntimo, que aún no pide seña variable),
            // ReservationController usa el total de la reserva como antes.
            // El mínimo real (S/ 30 por persona) es dinámico según party_size,
            // así que se valida en withValidator() más abajo, no acá.
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            // Token del invitado de Fermento que abrió este link personalizado
            // (campo oculto del form, ver home/fermento.blade.php). Si
            // corresponde al invitado especial "Pruebas", ReservationController
            // marca la reserva como is_test.
            'fermento_guest_token' => ['nullable', 'string', 'max:12'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_schedule_id.required' => 'Elige una fecha y hora disponible.',
            'event_schedule_id.exists' => 'El horario elegido ya no está disponible.',
            'customer_name.required' => 'Cuéntanos tu nombre.',
            'customer_email.required' => 'Necesitamos un correo para confirmar tu reserva.',
            'customer_phone.required' => 'Déjanos un teléfono de contacto.',
        ];
    }

    /**
     * Eventos con mesas propias (ej. Fermento) exigen elegir una mesa y acotan
     * el tamaño del grupo a su aforo. Eventos sin mesas (ej. Íntimo) siguen
     * reservando por cupo simple, con el tamaño de grupo fijo del evento.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $scheduleId = $this->input('event_schedule_id');
            if (! $scheduleId) {
                return;
            }

            $schedule = EventSchedule::with(['event', 'tables'])->find($scheduleId);
            if (! $schedule) {
                return;
            }

            $tables = $schedule->tables;
            $partySize = (int) $this->input('party_size');

            if ($tables->isNotEmpty()) {
                $tableId = $this->input('event_table_id');

                if (! $tableId) {
                    $validator->errors()->add('event_table_id', 'Elige una mesa disponible.');

                    return;
                }

                $table = $tables->firstWhere('id', (int) $tableId);

                if (! $table) {
                    $validator->errors()->add('event_table_id', 'Esa mesa no pertenece a esta fecha.');

                    return;
                }

                if ($partySize > $table->capacity_max) {
                    $validator->errors()->add('party_size', "La mesa elegida tiene capacidad para hasta {$table->capacity_max} personas.");
                }
            } elseif ($partySize !== (int) $schedule->event->party_size) {
                $validator->errors()->add('party_size', 'El tamaño de grupo para este evento es fijo.');
            }

            // La seña elegida no puede superar el total de la reserva (misma
            // fórmula que ReservationController::store: precio × personas si
            // el evento tiene mesas propias, precio plano si no).
            $depositAmount = $this->input('deposit_amount');
            if ($depositAmount !== null && $depositAmount !== '') {
                $total = $tables->isNotEmpty()
                    ? $schedule->event->price * $partySize
                    : $schedule->event->price;

                if ((float) $depositAmount > (float) $total) {
                    $validator->errors()->add('deposit_amount', 'La seña no puede ser mayor al total de la reserva.');
                }

                // Mínimo S/ 30 por persona (antes era un piso plano de S/ 20
                // sin importar el tamaño de grupo). $partySize ya viene
                // validado como entero ≥ 1 por la regla 'party_size'.
                $minDeposit = 30 * max($partySize, 1);
                if ((float) $depositAmount < $minDeposit) {
                    $validator->errors()->add('deposit_amount', "La seña mínima es S/ {$minDeposit} (S/ 30 por persona).");
                }
            }
        });
    }
}
