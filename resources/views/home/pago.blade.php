@extends('layout.layout')

@php
    $title = 'Pago';
    $subTitle = 'Confirma tu reserva';

    // Seña a coordinar por WhatsApp: el monto que el cliente eligió al
    // reservar (Fermento) o el total de la reserva si no se pidió seña
    // variable (Íntimo, hasta ahora).
    $depositAmount = $reservation->payment->amount ?? $reservation->total_amount;

    $waMessageLines = [
        'Hola! Quiero coordinar el pago de mi reserva ' . $reservation->code . '.',
        'Experiencia: ' . $reservation->event->name,
        'Fecha: ' . $reservation->schedule->date->format('d/m/Y') . ' — ' . \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) . ' h',
    ];

    if ($reservation->table) {
        $waMessageLines[] = 'Mesa: #' . $reservation->table->table_number;
    }

    $waMessageLines[] = 'Personas: ' . $reservation->party_size;
    $waMessageLines[] = 'A nombre de: ' . $reservation->customer_name;
    $waMessageLines[] = 'Monto de la seña: S/ ' . number_format($depositAmount, 2);

    $waNumber = config('services.reservas.whatsapp_number');
    $waLink = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode(implode("\n", $waMessageLines));
@endphp

@section('content')

<style>
    .pago-card {
        max-width: 560px; margin: 0 auto; border: 1px solid var(--border-color); padding: 46px;
        background: var(--body-bg-color-two);
    }
    .pago-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed var(--border-color); color: var(--body-color); font-size: 15px; }
    .pago-row span:last-child { color: var(--common-color-white); text-align: right; }
    .pago-total { display: flex; justify-content: space-between; padding: 22px 0 0; font-family: var(--heading-font-family); color: var(--heading-color); font-size: 26px; }
    .pago-btn {
        width: 100%; padding: 16px; margin-top: 30px; background: var(--yellow-color); color: #040D10;
        border: none; text-transform: uppercase; letter-spacing: .12em; font-size: 13px; cursor: pointer;
    }
    .pago-note { color: var(--body-color); font-size: 12px; text-align: center; margin-top: 16px; opacity: .75; }
</style>

<section>
    <div class="ak-height-150 ak-height-lg-70"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div style="letter-spacing:.28em;text-transform:uppercase;font-size:13px;color:var(--yellow-color);">
                Reserva {{ $reservation->code }}
            </div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title">Confirma el pago de tu mesa</h2>
        </div>
        <div class="ak-height-50 ak-height-lg-30"></div>

        <div class="pago-card">
            <div class="pago-row">
                <span>Experiencia</span>
                <span>{{ $reservation->event->name }}</span>
            </div>
            <div class="pago-row">
                <span>Fecha</span>
                <span>{{ $reservation->schedule->date->format('d/m/Y') }}</span>
            </div>
            <div class="pago-row">
                <span>Hora</span>
                <span>{{ \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) }}</span>
            </div>
            <div class="pago-row">
                <span>Personas</span>
                <span>{{ $reservation->party_size }}</span>
            </div>
            <div class="pago-row">
                <span>A nombre de</span>
                <span>{{ $reservation->customer_name }}</span>
            </div>
            <div class="pago-row">
                <span>Seña a coordinar</span>
                <span>S/ {{ number_format($depositAmount, 2) }}</span>
            </div>
            <div class="pago-total">
                <span>Total de la experiencia</span>
                <span>S/ {{ number_format($reservation->total_amount, 2) }}</span>
            </div>

            @if ($reservation->status === 'confirmed')
                <p style="color:var(--yellow-color);text-align:center;margin-top:24px;">
                    Esta reserva ya fue pagada y confirmada.
                </p>
                <a href="{{ route('reservas.confirmacion', $reservation->code) }}" class="pago-btn" style="display:block;text-align:center;text-decoration:none;">
                    Ver confirmación
                </a>
            @else
                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="pago-btn" style="display:block;text-align:center;text-decoration:none;">
                    Reservar por WhatsApp
                </a>
                <p class="pago-note">
                    Te escribimos por WhatsApp para coordinar el pago de tu seña (Yape, Plin o transferencia).
                    Tu reserva queda pendiente hasta que confirmemos que la recibimos.
                </p>
            @endif
        </div>
    </div>
    <div class="ak-height-150 ak-height-lg-70"></div>
</section>

@endsection
