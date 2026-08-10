@extends('layout.layout')

@php
    $title = 'Confirmación';
    $subTitle = 'Reserva confirmada';
@endphp

@section('content')

<section>
    <div class="ak-height-150 ak-height-lg-70"></div>
    <div class="container" style="max-width:640px;text-align:center;">
        <div style="letter-spacing:.28em;text-transform:uppercase;font-size:13px;color:var(--yellow-color);">
            Reserva confirmada
        </div>
        <div class="ak-height-20"></div>
        <h2 class="ak-section-title anim-title">Los esperamos en la mesa, {{ $reservation->customer_name }}</h2>
        <div class="ak-height-30"></div>
        <p style="color:var(--body-color);font-size:16px;line-height:1.9;">
            Tu código de reserva es <strong style="color:var(--heading-color);">{{ $reservation->code }}</strong>.
            El {{ $reservation->schedule->date->format('d/m/Y') }} a las
            {{ \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) }} h,
            en {{ $reservation->event->name }}, para {{ $reservation->party_size }} personas.
        </p>
        <p style="color:var(--body-color);font-size:14px;">
            Enviamos la confirmación a {{ $reservation->customer_email }}.
        </p>
        <div class="ak-height-40"></div>
        <a href="{{ url('/') }}" class="ak-btn style-5" style="display:inline-block;">
            Volver al inicio
        </a>
    </div>
    <div class="ak-height-150 ak-height-lg-70"></div>
</section>

@endsection
