@extends('layout.layout')

@php
    $title = 'Experiencias';
    $subTitle = 'Experiencias';
@endphp

@section('content')

<!-- Start Experiencias Intro -->
<section class="experiencias-section">
    <div class="ak-height-150 ak-height-lg-60"></div>
    <div class="container">
        <div class="experiencias-intro">
            <p class="experiencias-eyebrow manifiesto-reveal">Experiencias</p>
            <p class="experiencias-lead manifiesto-reveal">
                Más allá de la carta, CLAN abre otras formas de sentarse alrededor del
                fogón. Cada experiencia tiene su propio ritmo, su propio grupo y su
                propia narrativa.
            </p>
        </div>
    </div>
    <div class="ak-height-100 ak-height-lg-60"></div>
</section>
<!-- End Experiencias Intro -->

<!-- Start Experiencias Grid -->
<section class="experiencias-section">
    <div class="container">
        <div class="experiencias-grid">

            <a href="{{ route('showclinic') }}" class="experiencias-card is-active manifiesto-reveal">
                <span class="experiencias-card-tag">Evento especial</span>
                <h3 class="experiencias-card-title">Show Clinic</h3>
                <p class="experiencias-card-text">
                    Una experiencia en desarrollo, donde la cocina se convierte en
                    escenario y el proceso creativo de CLAN se comparte en vivo.
                </p>
                <span class="experiencias-card-link">Ver invitación &rarr;</span>
            </a>

            <a href="https://intimo.kavernario.com" target="_blank" rel="noopener noreferrer" class="experiencias-card is-active manifiesto-reveal">
                <span class="experiencias-card-tag">Reservas abiertas</span>
                <h3 class="experiencias-card-title">Íntimo</h3>
                <p class="experiencias-card-text">
                    Una mesa reducida, pensada para una conversación cercana con el
                    fuego y con quienes cocinan.
                </p>
                <span class="experiencias-card-link">Ver experiencia &rarr;</span>
            </a>

        </div>
    </div>
    <div class="ak-height-150 ak-height-lg-60"></div>
</section>
<!-- End Experiencias Grid -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
        gsap.utils.toArray('.manifiesto-reveal').forEach(function (el) {
            gsap.fromTo(el,
                { autoAlpha: 0, y: 30 },
                {
                    autoAlpha: 1, y: 0, duration: 1, ease: 'power2.out',
                    scrollTrigger: { trigger: el, start: 'top 90%' }
                }
            );
        });
    });
</script>

@endsection
