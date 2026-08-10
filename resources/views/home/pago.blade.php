@extends('layout.layout')

@php
    $title = 'Pago';
    $subTitle = 'Confirma tu reserva';
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
            <div class="pago-total">
                <span>Total</span>
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
                <form method="POST" action="{{ route('reservas.pago.procesar', $reservation->code) }}">
                    @csrf
                    <button type="submit" class="pago-btn">Pagar S/ {{ number_format($reservation->total_amount, 2) }}</button>
                </form>
                <p class="pago-note">
                    Pasarela de pago en modo de prueba. Aquí se integrará el cobro real (Culqi / Mercado Pago / Stripe).
                </p>
            @endif
        </div>
    </div>
    <div class="ak-height-150 ak-height-lg-70"></div>
</section>

@endsection
