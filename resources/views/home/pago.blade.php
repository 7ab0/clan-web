@extends('layout.layout')

@php
    $title = 'Pago';
    $subTitle = 'Confirma tu reserva';
    $isFermento = $reservation->event->slug === 'fermento';

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

    // Cache-buster por fecha de modificación, mismo criterio que fermento.blade.php.
    $img = fn ($name) => asset('assets/img/fermento/' . $name)
        . (file_exists(public_path('assets/img/fermento/' . $name)) ? '?v=' . filemtime(public_path('assets/img/fermento/' . $name)) : '');
@endphp

@section('content')

<style>
    /* ===== Ocultar cabecera y elementos genéricos del template en esta página ===== */
    header.ak-site_header,
    .ak-commmon-hero,
    footer .ak-footer {
        display: none !important;
    }
    #preloader { display: none !important; }
</style>

@if ($isFermento)
    <style>
        /* ===== Tipografías de marca CLAN (mismo subset que confirmacion.blade.php) ===== */
        @font-face { font-family: 'ClanCinzel'; src: url('{{ asset('assets/fonts/clan/CinzelDecorative-Regular.ttf') }}') format('truetype'); font-weight: 400; font-style: normal; font-display: swap; }
        @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Regular.ttf') }}') format('truetype'); font-weight: 400; font-style: normal; font-display: swap; }
        @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Medium.ttf') }}') format('truetype'); font-weight: 500; font-style: normal; font-display: swap; }

        :root {
            --fermento-yellow: #FBB12F;
            --fermento-bg: #1A1410;
            --fermento-panel-bg: #221A13;
            --fermento-white: #F6EEE1;
            --fermento-label: rgba(246,238,225,.65);
            --fermento-separator: rgba(251,177,47,.15);
            --fermento-border: rgba(251,177,47,.25);
            --fermento-note: rgba(246,238,225,.55);
        }

        .pago-fermento-kicker {
            font-family: 'ClanPoppins', sans-serif; font-weight: 400;
            letter-spacing: .2em; text-transform: uppercase; font-size: 13px;
            color: var(--fermento-yellow); text-align: center; margin: 0 0 16px;
        }
        .pago-fermento-title {
            font-family: 'ClanCinzel', serif; font-weight: 400;
            color: var(--fermento-yellow); font-size: clamp(30px, 4.5vw, 52px);
            text-align: center; margin: 0 0 60px;
        }
        .pago-fermento-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px;
            max-width: 1600px; margin: 0 auto;
        }
        .pago-fermento-photo {
            width: 100%; aspect-ratio: 780 / 700; object-fit: cover; display: block;
        }
        .pago-fermento-panel {
            background: var(--fermento-panel-bg); border: 1px solid var(--fermento-border);
            padding: 40px 43px; display: flex; flex-direction: column;
        }
        .pago-fermento-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 17px 0; border-bottom: 1px solid var(--fermento-separator);
            font-family: 'ClanPoppins', sans-serif; font-size: 15px;
        }
        .pago-fermento-row span:first-child { color: var(--fermento-label); }
        .pago-fermento-row span:last-child { color: var(--fermento-white); font-weight: 500; text-align: right; }
        .pago-fermento-total {
            display: flex; justify-content: space-between; align-items: center;
            padding: 26px 0 34px; font-family: 'ClanCinzel', serif; color: var(--fermento-yellow); font-size: 22px;
        }
        .pago-fermento-btn {
            width: 100%; padding: 20px; background: var(--fermento-yellow); color: var(--fermento-bg);
            border: none; font-family: 'ClanPoppins', sans-serif; font-weight: 500;
            text-transform: uppercase; letter-spacing: .1em; font-size: 14px; cursor: pointer;
            text-align: center; text-decoration: none; display: block;
        }
        .pago-fermento-note {
            font-family: 'ClanPoppins', sans-serif; color: var(--fermento-note); font-size: 12px;
            text-align: center; margin-top: 16px; line-height: 1.6;
        }
        @media (max-width: 991px) {
            .pago-fermento-grid { grid-template-columns: 1fr; }
            .pago-fermento-photo { aspect-ratio: 16 / 9; }
        }
    </style>

    <section style="background: var(--fermento-bg); padding: 90px 20px 100px;">
        <div class="pago-fermento-kicker">Reserva {{ $reservation->code }}</div>
        <h2 class="pago-fermento-title">Confirma el pago de tu mesa</h2>

        <div class="pago-fermento-grid">
            <img class="pago-fermento-photo" src="{{ $img('mesa-comunitaria.jpg') }}" alt="Mesa comunitaria de Fermento">

            <div class="pago-fermento-panel">
                <div class="pago-fermento-row">
                    <span>Experiencia</span>
                    <span>{{ $reservation->event->name }}</span>
                </div>
                <div class="pago-fermento-row">
                    <span>Fecha</span>
                    <span>{{ $reservation->schedule->date->format('d/m/Y') }}</span>
                </div>
                <div class="pago-fermento-row">
                    <span>Hora</span>
                    <span>{{ \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) }}</span>
                </div>
                <div class="pago-fermento-row">
                    <span>Personas</span>
                    <span>{{ $reservation->party_size }}</span>
                </div>
                <div class="pago-fermento-row">
                    <span>A nombre de</span>
                    <span>{{ $reservation->customer_name }}</span>
                </div>
                <div class="pago-fermento-row">
                    <span>Seña a coordinar</span>
                    <span>S/ {{ number_format($depositAmount, 2) }}</span>
                </div>
                <div class="pago-fermento-total">
                    <span>Total de la experiencia</span>
                    <span>S/ {{ number_format($reservation->total_amount, 2) }}</span>
                </div>

                @if ($reservation->status === 'confirmed')
                    <a href="{{ route('reservas.confirmacion', $reservation->code) }}" class="pago-fermento-btn">
                        Ver confirmación
                    </a>
                    <p class="pago-fermento-note">Esta reserva ya fue pagada y confirmada.</p>
                @else
                    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="pago-fermento-btn">
                        Reservar por WhatsApp
                    </a>
                    <p class="pago-fermento-note">
                        Te escribimos por WhatsApp para coordinar el pago de tu seña (Yape, Plin o transferencia).
                        Tu reserva queda pendiente hasta que confirmemos que la recibimos.
                    </p>
                @endif
            </div>
        </div>
    </section>
@else
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
@endif

@endsection
