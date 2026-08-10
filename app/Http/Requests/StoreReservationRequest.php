<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'party_size' => ['required', 'integer', 'min:2', 'max:2'],
            'relationship_type' => ['nullable', 'string', 'max:60'],
            'notes' => ['nullable', 'string', 'max:500'],
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
}
