@extends('layout.layout')

@php
    $title = 'Fermento';
    $subTitle = 'Fermento';
    $img = fn ($name) => asset('assets/img/fermento/' . $name);
    $platoImg = fn ($name) => asset('assets/img/' . $name);
@endphp

@section('content')

<style>
    /* ===== Ocultar cabecera y elementos genéricos del template en esta página ===== */
    header.ak-site_header,
    .ak-commmon-hero,
    footer .ak-footer {
        display: none !important;
    }

    /* ===== Tipografías de marca CLAN ===== */
    @font-face { font-family: 'ClanCinzel'; src: url('{{ asset('assets/fonts/clan/CinzelDecorative-Regular.ttf') }}') format('truetype'); font-weight: 400; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanCinzel'; src: url('{{ asset('assets/fonts/clan/CinzelDecorative-Bold.ttf') }}') format('truetype'); font-weight: 700; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanCinzel'; src: url('{{ asset('assets/fonts/clan/CinzelDecorative-Black.ttf') }}') format('truetype'); font-weight: 900; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Regular.ttf') }}') format('truetype'); font-weight: 400; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Italic.ttf') }}') format('truetype'); font-weight: 400; font-style: italic; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Medium.ttf') }}') format('truetype'); font-weight: 500; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-SemiBold.ttf') }}') format('truetype'); font-weight: 600; font-style: normal; font-display: swap; }
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Bold.ttf') }}') format('truetype'); font-weight: 700; font-style: normal; font-display: swap; }

    /*
     * Paleta placeholder de Fermento — todavía sin diseño final de Figma.
     * Base cálida (#1a1410, cenizas) + crema + dorado principal CLAN (#FBB12F),
     * distinta del dorado alterno (#C9A961) que usa Íntimo.
     */
    :root {
        --body-font-family: 'ClanPoppins', sans-serif;
        --heading-font-family: 'ClanCinzel', serif;
        --yellow-color: #FBB12F;
        --heading-color: #FBB12F;
        --body-color: #D8CDBE;
        --body-bg-color: #1A1410;
        --body-bg-color-two: #221A13;
        --border-color: #3B3024;
        --common-color-white: #F6EEE1;
    }

    /* ===== Fermento — estilos propios de esta página ===== */
    .fermento-section { position: relative; }
    .fermento-eyebrow {
        font-family: var(--body-font-family);
        letter-spacing: .28em;
        text-transform: uppercase;
        font-size: 13px;
        color: var(--yellow-color);
        opacity: .9;
    }
    .fermento-logo { max-width: 130px; height: auto; margin-bottom: 22px; opacity: .95; }

    /* Hero: <img> real (no background-image) para controlar el encuadre por breakpoint */
    .fermento-hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
    }
    .fermento-hero-media { position: absolute; inset: 0; z-index: 0; }
    .fermento-hero-media img {
        width: 100%; height: 100%; object-fit: cover; object-position: center 22%; display: block;
    }
    .fermento-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(26,20,16,.55) 0%, rgba(26,20,16,.32) 28%, rgba(26,20,16,.72) 62%, rgba(26,20,16,.96) 100%);
    }
    .fermento-hero-inner { position: relative; z-index: 1; padding: 0 0 90px; max-width: 780px; }
    .fermento-hero h1 {
        font-family: var(--heading-font-family);
        color: var(--heading-color);
        font-size: clamp(48px, 7vw, 92px);
        line-height: 1.05;
        margin: 18px 0 14px;
        text-shadow: 0 4px 26px rgba(0,0,0,.55);
    }
    .fermento-hero-transmute {
        font-family: var(--heading-font-family);
        font-style: italic;
        color: var(--yellow-color);
        font-size: clamp(19px, 2.4vw, 27px);
        margin: 0 0 18px;
        text-shadow: 0 2px 16px rgba(0,0,0,.5);
    }
    .fermento-hero p.tagline {
        font-family: var(--body-font-family);
        font-style: italic;
        font-weight: 300;
        color: var(--common-color-white);
        font-size: clamp(17px, 2vw, 21px);
        max-width: 580px;
        opacity: .95;
        text-shadow: 0 2px 14px rgba(0,0,0,.5);
    }
    .fermento-hero-ctas { display: flex; gap: 18px; margin-top: 42px; flex-wrap: wrap; }
    .fermento-btn {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 16px 30px;
        border: 1px solid var(--yellow-color);
        color: var(--yellow-color);
        text-transform: uppercase; letter-spacing: .14em; font-size: 13px;
        transition: all .3s ease;
        font-family: var(--body-font-family);
        cursor: pointer;
        background: transparent;
    }
    .fermento-btn.solid { background: var(--yellow-color); color: #1A1410; }
    .fermento-btn:hover { background: var(--yellow-color); color: #1A1410; }
    .fermento-btn:disabled { opacity: .4; cursor: not-allowed; }
    .fermento-btn:disabled:hover { background: transparent; color: var(--yellow-color); }

    .fermento-narrow { max-width: 720px; margin: 0 auto; text-align: center; }
    .fermento-story p { color: var(--body-color); font-size: 16px; line-height: 1.9; margin-bottom: 20px; }
    .fermento-quote {
        font-family: var(--heading-font-family);
        font-size: clamp(24px, 2.8vw, 32px);
        color: var(--heading-color);
        line-height: 1.5;
        margin: 0 0 30px;
    }

    /* El concepto — galería de 2 fotos */
    .fermento-concept-gallery {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
        max-width: 1000px; margin: 56px auto 0;
    }
    .fermento-concept-gallery img {
        width: 100%; height: 460px; object-fit: cover; filter: grayscale(10%);
        border: 1px solid var(--border-color);
    }

    /* Nuestra cocina */
    .fermento-kitchen-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 26px; }
    .fermento-kitchen-card { text-align: center; }
    .fermento-kitchen-photo { overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 20px; }
    .fermento-kitchen-photo img {
        width: 100%; aspect-ratio: 3 / 4; height: auto; object-fit: cover;
        filter: grayscale(8%);
        transition: filter .5s ease, transform .5s ease;
    }
    .fermento-kitchen-card:hover .fermento-kitchen-photo img { filter: grayscale(0%); transform: scale(1.04); }
    /* clan-menu-tormenta-lago.jpg trae un borde blanco de fábrica en el
       ~5.5% inferior; como su ratio nativo (1280x1600) es casi igual al del
       contenedor, object-fit:cover no recorta verticalmente por sí solo.
       Se fuerza el recorte escalando desde el borde superior. */
    .fermento-kitchen-photo.is-crop-bottom img { transform-origin: center top; transform: scale(1.12); }
    .fermento-kitchen-card:hover .fermento-kitchen-photo.is-crop-bottom img { transform: scale(1.18); }
    .fermento-kitchen-card h5 { color: var(--heading-color); font-size: 16px; margin-bottom: 8px; }
    .fermento-kitchen-card p { color: var(--body-color); font-size: 13px; line-height: 1.65; }
    .fermento-kitchen-note {
        text-align: center; color: var(--body-color); font-size: 14px; margin-top: 40px; opacity: .8; font-style: italic;
    }

    /* Banda sonora — bloque editorial, no lista de tarjetas */
    .fermento-soundtrack { max-width: 860px; margin: 0 auto; }
    .fermento-track {
        display: flex; align-items: baseline; justify-content: space-between; gap: 30px;
        padding: 30px 0; border-bottom: 1px solid var(--border-color);
    }
    .fermento-track:first-child { padding-top: 0; }
    .fermento-track:last-child { border-bottom: none; padding-bottom: 0; }
    .fermento-track .artist {
        font-family: var(--heading-font-family); color: var(--heading-color);
        font-size: clamp(26px, 4vw, 40px); letter-spacing: .01em; white-space: nowrap;
    }
    .fermento-track .mood {
        font-family: var(--body-font-family); font-style: italic; color: var(--body-color);
        font-size: 15px; text-align: right; max-width: 300px;
    }

    /* Foto banner (Otros momentos) */
    .fermento-banner-photo {
        width: 100%; max-width: 1100px; margin: 0 auto; display: block;
        height: min(56vh, 540px); object-fit: cover; filter: grayscale(6%);
        border: 1px solid var(--border-color);
    }
    .fermento-banner-caption {
        text-align: center; color: var(--body-color); font-size: 13px; letter-spacing: .04em;
        margin-top: 18px; font-style: italic; opacity: .85;
    }

    .fermento-price-card {
        border: 1px solid var(--yellow-color); padding: 50px 40px; text-align: center; max-width: 480px; margin: 0 auto;
        background: var(--body-bg-color-two);
    }
    .fermento-price-card .amount { font-family: var(--heading-font-family); color: var(--heading-color); font-size: 52px; }
    .fermento-price-card .per { color: var(--body-color); font-size: 13px; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 26px; }
    .fermento-price-card ul { list-style: none; padding: 0; margin: 0 0 30px; text-align: left; }
    .fermento-price-card ul li { color: var(--body-color); padding: 10px 0; border-bottom: 1px dashed var(--border-color); font-size: 14px; line-height: 1.6; }
    .fermento-price-card ul li:last-child { border-bottom: none; }

    .fermento-book-form {
        max-width: 680px; margin: 0 auto; background: var(--body-bg-color-two); border: 1px solid var(--border-color); padding: 46px;
    }
    .fermento-field { margin-bottom: 24px; }
    .fermento-field label { display: block; color: var(--heading-color); font-size: 13px; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 10px; }
    .fermento-field input, .fermento-field select, .fermento-field textarea {
        width: 100%; background: rgba(255,255,255,.02); border: 1px solid var(--border-color); color: var(--common-color-white);
        padding: 14px 16px; font-family: var(--body-font-family); font-size: 15px;
    }
    .fermento-field input:focus, .fermento-field select:focus, .fermento-field textarea:focus { outline: none; border-color: var(--yellow-color); }
    .fermento-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .fermento-alert-error {
        border: 1px solid #b95a4a; color: #f3c9c1; padding: 16px 20px; margin-bottom: 26px; font-size: 14px;
    }
    .fermento-alert-error ul { margin: 0; padding-left: 18px; }
    .fermento-note { color: var(--body-color); font-size: 13px; margin-top: 12px; opacity: .75; }

    /* Grid de mesas */
    .fermento-tables-empty { color: var(--body-color); font-size: 14px; opacity: .75; }
    .fermento-tables-legend { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 16px; }
    .fermento-tables-legend .item { display: inline-flex; align-items: center; gap: 8px; font-size: 11px; color: var(--body-color); text-transform: uppercase; letter-spacing: .05em; }
    .fermento-tables-legend .swatch { width: 12px; height: 12px; border-radius: 50%; display: inline-block; flex: none; }
    .fermento-tables-legend .swatch.available { border: 1px solid var(--yellow-color); background: transparent; }
    .fermento-tables-legend .swatch.selected { background: var(--yellow-color); }
    .fermento-tables-legend .swatch.taken { background: #5a4d3d; opacity: .7; }

    .fermento-tables-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    .fermento-table-btn {
        border: 1px solid var(--border-color); background: rgba(255,255,255,.02); color: var(--common-color-white);
        padding: 16px 8px; text-align: center; cursor: pointer; font-family: var(--body-font-family);
        transition: all .2s ease;
    }
    .fermento-table-btn .n { display: block; font-family: var(--heading-font-family); color: var(--heading-color); font-size: 17px; }
    .fermento-table-btn .cap { display: block; font-size: 11px; color: var(--body-color); margin-top: 5px; text-transform: uppercase; letter-spacing: .04em; }
    .fermento-table-btn:hover:not(:disabled) { border-color: var(--yellow-color); background: rgba(251,177,47,.08); }
    .fermento-table-btn.is-selected { border-color: var(--yellow-color); background: var(--yellow-color); }
    .fermento-table-btn.is-selected .n, .fermento-table-btn.is-selected .cap { color: #1A1410; }
    .fermento-table-btn:disabled { opacity: .4; cursor: not-allowed; border-style: dashed; }
    .fermento-table-btn:disabled .cap { text-decoration: line-through; }
    .fermento-table-hint { color: var(--body-color); font-size: 13px; margin-top: 16px; }
    .fermento-table-hint strong { color: var(--heading-color); }

    /* Footer propio */
    .fermento-footer { background: #120D09; border-top: 1px solid var(--border-color); padding: 70px 0 40px; }
    .fermento-footer-inner { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 24px; }
    .fermento-footer img.icon { width: 64px; height: auto; }
    .fermento-footer .line { color: var(--body-color); font-size: 15px; }
    .fermento-footer .line a { color: var(--body-color); text-decoration: none; border-bottom: 1px solid var(--border-color); }
    .fermento-footer .line a:hover { color: var(--yellow-color); border-color: var(--yellow-color); }
    .fermento-social { display: flex; gap: 20px; }
    .fermento-social a {
        width: 42px; height: 42px; border: 1px solid var(--border-color); border-radius: 50%;
        display: flex; align-items: center; justify-content: center; color: var(--body-color); transition: all .3s ease;
    }
    .fermento-social a:hover { border-color: var(--yellow-color); color: var(--yellow-color); }
    .fermento-copy { color: #6b6055; font-size: 12px; margin-top: 20px; }

    @media (max-width: 991px) {
        .fermento-kitchen-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 767px) {
        .fermento-hero-media img { object-position: 62% center; }
        .fermento-field-row { grid-template-columns: 1fr; }
        .fermento-concept-gallery { grid-template-columns: 1fr; }
        .fermento-concept-gallery img { height: 320px; }
        .fermento-soundtrack .fermento-track { flex-direction: column; align-items: flex-start; gap: 6px; }
        .fermento-track .mood { text-align: left; max-width: 100%; }
        .fermento-banner-photo { height: 320px; }
        .fermento-tables-grid { grid-template-columns: repeat(3, 1fr); }
        .fermento-tables-legend { gap: 14px; }
    }

    /* Preloader genérico del tema (compartido con el resto del sitio CLAN) —
       oculto solo en esta página, Fermento pinta el suyo propio abajo. */
    #preloader { display: none !important; }

    .fermento-preloader {
        position: fixed; inset: 0; z-index: 99999;
        background: var(--body-bg-color);
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 22px;
        transition: opacity .7s ease, visibility .7s ease;
    }
    .fermento-preloader.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    .fermento-preloader img { width: 72px; height: auto; animation: fermento-preloader-pulse 1.8s ease-in-out infinite; }
    .fermento-preloader p {
        font-family: var(--heading-font-family); color: var(--heading-color);
        letter-spacing: .06em; font-size: clamp(16px, 2.4vw, 22px); text-align: center; margin: 0; padding: 0 20px;
    }
    @keyframes fermento-preloader-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .55; transform: scale(.9); }
    }
    body.fermento-preloading { overflow: hidden; }

    /* Toggle de música de fondo */
    .fermento-audio-toggle {
        position: fixed; bottom: 1.75rem; right: 1.75rem; width: 48px; height: 48px; border-radius: 50%;
        border: 1px solid var(--border-color); background: rgba(26,20,16,.8); backdrop-filter: blur(10px);
        color: var(--yellow-color); display: flex; align-items: center; justify-content: center; cursor: pointer;
        z-index: 9000; transition: all .3s ease; box-shadow: 0 10px 30px rgba(0,0,0,.35);
    }
    .fermento-audio-toggle:hover { transform: scale(1.08); border-color: var(--yellow-color); }
    .fermento-audio-toggle .icon-unmuted { display: none; }
    .fermento-audio-toggle.is-unmuted .icon-muted { display: none; }
    .fermento-audio-toggle.is-unmuted .icon-unmuted { display: block; }
    @media (max-width: 480px) {
        .fermento-audio-toggle { bottom: 1.25rem; right: 1.25rem; width: 42px; height: 42px; }
    }
</style>

<!-- Preloader propio de Fermento: "Disfruta nuestra Experiencia" + libélula CLAN -->
<div class="fermento-preloader" id="fermentoPreloader">
    <img src="{{ asset('assets/img/icono-libelula.png') }}" alt="CLAN">
    <p>Disfruta nuestra Experiencia</p>
</div>

<!-- Hero -->
<section class="fermento-hero">
    <div class="fermento-hero-media">
        <img src="{{ $img('hero-chefs.jpg') }}" alt="Chefs de CLAN y FORNO">
        <div class="fermento-hero-overlay"></div>
    </div>
    <div class="container fermento-hero-inner">
        <img src="{{ $img('clan-logo.png') }}" alt="CLAN" class="fermento-logo">
        <div class="fermento-eyebrow">CLAN x FORNO</div>
        <h1>{{ $event->name }}</h1>
        <p class="fermento-hero-transmute">Transmutación de la masa madre</p>
        <p class="tagline">"{{ $event->tagline }}"</p>
        <div class="fermento-hero-ctas">
            <a href="#reservar" class="fermento-btn solid">Reservar mi mesa</a>
            <a href="#concepto" class="fermento-btn">Conocer el concepto</a>
        </div>
    </div>
</section>

<!-- El concepto -->
<section id="concepto" class="fermento-section">
    <div class="ak-height-150 ak-height-lg-70"></div>
    <div class="container">
        <div class="fermento-narrow">
            <div class="fermento-eyebrow">El concepto</div>
            <div class="ak-height-20"></div>
            <p class="fermento-quote">Todo lo que fermenta empieza igual: tiempo y fuego.</p>
            <div class="fermento-story">
                <p>{{ $event->description }}</p>
                <p>
                    Una sola mesa larga, bajo carpa, con el horno de leña encendido toda la noche.
                    No hay menú fijo por persona: hay una mesa comunitaria donde circulan pizzas,
                    platos de autor y copas de vino, en el mismo ritmo con el que se cocina al fuego.
                </p>
            </div>
        </div>
        <div class="fermento-concept-gallery">
            <img src="{{ $img('mesa-comunitaria.jpg') }}" alt="Mesa comunitaria — Fermento CLAN x FORNO">
            <img src="{{ $img('momento-1.jpg') }}" alt="Un brindis en la mesa — Fermento CLAN x FORNO">
        </div>
    </div>
    <div class="ak-height-150 ak-height-lg-70"></div>
</section>

<!-- Nuestra cocina -->
<section class="fermento-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="fermento-eyebrow">Nuestra cocina</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title" style="max-width:640px;margin:0 auto;">
                El lado CLAN de la colaboración
            </h2>
        </div>
        <div class="ak-height-60 ak-height-lg-30"></div>
        @php
            $platosClan = [
                ['img' => 'clan-menu-geometria-siwichi.jpg', 'nombre' => 'Geometría del Siwichi', 'desc' => 'Ceviche con leche de tigre de guiñapo, tumbo y maracuyá.'],
                ['img' => 'clan-menu-dialecto-fuego.jpg', 'nombre' => 'Dialecto del Fuego', 'desc' => 'Lomo fino flameado con langostinos y conchas de abanico.'],
                ['img' => 'clan-menu-rumor-hollin.jpg', 'nombre' => 'El Rumor del Hollín', 'desc' => 'Picaña ahumada con lasagna de cinghiale.'],
                ['img' => 'clan-menu-tormenta-lago.jpg', 'nombre' => 'Tormenta en el Lago', 'desc' => 'Trucha curada con tubérculos andinos y mix de nuestro huerto.', 'cropBottom' => true],
            ];
        @endphp
        <div class="fermento-kitchen-grid">
            @foreach ($platosClan as $plato)
                <div class="fermento-kitchen-card">
                    <div class="fermento-kitchen-photo{{ ($plato['cropBottom'] ?? false) ? ' is-crop-bottom' : '' }}">
                        <img src="{{ $platoImg($plato['img']) }}" alt="{{ $plato['nombre'] }} — CLAN">
                    </div>
                    <h5>{{ $plato['nombre'] }}</h5>
                    <p>{{ $plato['desc'] }}</p>
                </div>
            @endforeach
        </div>
        <p class="fermento-kitchen-note">
            El lado FORNO de la colaboración se suma pronto, cuando tengamos su material listo.
        </p>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- La banda sonora de la noche -->
<section class="fermento-section">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="fermento-eyebrow">Ambientación</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title" style="max-width:640px;margin:0 auto;">
                La banda sonora de la noche
            </h2>
        </div>
        <div class="ak-height-60 ak-height-lg-30"></div>
        <div class="fermento-soundtrack">
            <div class="fermento-track">
                <div class="artist">Black Pumas</div>
                <div class="mood">Soul cálido y orgánico.</div>
            </div>
            <div class="fermento-track">
                <div class="artist">Alabama Shakes</div>
                <div class="mood">Rock con alma, crudo y directo.</div>
            </div>
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Otros momentos -->
<section class="fermento-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="fermento-eyebrow">La mesa</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title">Encendido toda la noche</h2>
        </div>
        <div class="ak-height-50 ak-height-lg-30"></div>
        <a href="{{ $img('momento-2.jpg') }}" target="_blank" rel="noopener">
            <img src="{{ $img('momento-2.jpg') }}" alt="El horno de leña de Fermento, encendido" class="fermento-banner-photo">
        </a>
        <p class="fermento-banner-caption">El horno de leña, encendido desde antes de que lleguen los primeros invitados.</p>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Precio -->
<section class="fermento-section">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="fermento-eyebrow">La inversión</div>
        </div>
        <div class="ak-height-40 ak-height-lg-20"></div>
        <div class="fermento-price-card">
            <div class="amount">S/ {{ number_format($event->price, 0) }}</div>
            <div class="per">Por persona</div>
            <ul>
                <li>Pizzas al horno de leña y platos de autor de CLAN</li>
                <li>Vinos seleccionados para acompañar la mesa</li>
                <li>Mesa comunitaria bajo carpa, servicio a lo largo de la noche</li>
                <li>12 mesas disponibles por fecha, cada una para 2 a 4 personas</li>
            </ul>
            <a href="#reservar" class="fermento-btn solid" style="width:100%;justify-content:center;">Reservar mi mesa</a>
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Reservas -->
<section id="reservar" class="fermento-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="fermento-eyebrow">Reserva</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title">Elige tu fecha y tu mesa</h2>
        </div>
        <div class="ak-height-50 ak-height-lg-30"></div>

        <form class="fermento-book-form" method="POST" action="{{ route('fermento.reservar') }}" id="fermentoForm">
            @csrf

            @if ($errors->any())
                <div class="fermento-alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="fermento-field">
                <label for="event_schedule_id">1 · Fecha</label>
                @if ($schedulesByDate->isEmpty())
                    <p class="fermento-tables-empty">
                        No hay fechas disponibles por ahora. Escríbenos y te avisamos apenas se abra un nuevo cupo.
                    </p>
                @else
                    <select name="event_schedule_id" id="event_schedule_id" required>
                        <option value="" disabled {{ old('event_schedule_id') ? '' : 'selected' }}>Selecciona una fecha</option>
                        @foreach ($schedulesByDate as $group)
                            @foreach ($group['schedules'] as $schedule)
                                <option value="{{ $schedule->id }}" {{ (string) old('event_schedule_id') === (string) $schedule->id ? 'selected' : '' }}>
                                    {{ $group['label'] }} — {{ \Illuminate\Support\Str::of($schedule->start_time)->substr(0, 5) }} h
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="fermento-field">
                <label>2 · Mesa</label>
                <div class="fermento-tables-legend">
                    <span class="item"><span class="swatch available"></span> Disponible</span>
                    <span class="item"><span class="swatch selected"></span> Elegida</span>
                    <span class="item"><span class="swatch taken"></span> Ocupada</span>
                </div>
                <div id="fermentoTablesGrid" class="fermento-tables-grid"></div>
                <p id="fermentoTablesEmpty" class="fermento-tables-empty">Elige primero una fecha para ver las mesas disponibles.</p>
                <p id="fermentoTableHint" class="fermento-table-hint" style="display:none;"></p>
                <input type="hidden" name="event_table_id" id="event_table_id" value="{{ old('event_table_id') }}">
            </div>

            <div class="fermento-field">
                <label style="margin-top:6px;">3 · Tus datos</label>
            </div>

            <div class="fermento-field-row">
                <div class="fermento-field">
                    <label for="customer_name">Nombre</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required>
                </div>
                <div class="fermento-field">
                    <label for="customer_phone">Teléfono</label>
                    <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" required>
                </div>
            </div>

            <div class="fermento-field-row">
                <div class="fermento-field">
                    <label for="customer_email">Correo</label>
                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" required>
                </div>
                <div class="fermento-field">
                    <label for="party_size">Personas</label>
                    <input type="number" name="party_size" id="party_size" min="1" max="4" value="{{ old('party_size', 2) }}" required>
                </div>
            </div>

            <div class="fermento-field">
                <label for="notes">¿Algo que debamos saber? (opcional)</label>
                <textarea name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="fermento-btn solid" style="width:100%;justify-content:center;border:none;">
                Continuar al pago
            </button>
            <p class="fermento-note" style="text-align:center;">
                No se realiza ningún cobro todavía. En el siguiente paso confirmas el pago de tu reserva.
            </p>
        </form>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Footer propio de Fermento -->
<footer class="fermento-footer">
    <div class="container fermento-footer-inner">
        <img src="{{ $img('icono-clan.png') }}" alt="CLAN" class="icon">

        <div class="line">
            Reservas al <a href="tel:+51965609626">965 609 626</a>
        </div>

        <div class="line">
            <a href="https://maps.app.goo.gl/fWUcmpiBmsPHM8LAA" target="_blank" rel="noopener">
                Calle Santa Catalina 105 – Arequipa
            </a>
        </div>

        <div class="fermento-social">
            <a href="https://www.facebook.com/Clan.rest.aqp" target="_blank" rel="noopener" aria-label="Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.89h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94Z"/></svg>
            </a>
            <a href="https://www.instagram.com/clan_rest_/" target="_blank" rel="noopener" aria-label="Instagram">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07c-1.28.06-2.15.26-2.91.56-.79.31-1.46.72-2.13 1.38C1.25 2.67.84 3.34.53 4.13c-.3.76-.5 1.63-.56 2.91C-.03 8.33-.04 8.74-.04 12s.01 3.67.07 4.95c.06 1.28.26 2.15.56 2.91.31.79.72 1.46 1.38 2.13.66.66 1.34 1.07 2.13 1.38.76.3 1.63.5 2.91.56 1.28.06 1.69.07 4.95.07s3.67-.01 4.95-.07c1.28-.06 2.15-.26 2.91-.56.79-.31 1.46-.72 2.13-1.38.66-.66 1.07-1.34 1.38-2.13.3-.76.5-1.63.56-2.91.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.06-1.28-.26-2.15-.56-2.91-.31-.79-.72-1.46-1.38-2.13C21.33 1.25 20.66.84 19.87.53c-.76-.3-1.63-.5-2.91-.56C15.67-.03 15.26-.04 12-.04Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.16A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm6.41-10.4a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg>
            </a>
        </div>

        <div class="fermento-copy">CLAN · Hambre de Crear</div>
    </div>
</footer>

<!-- Música de fondo: mismo patrón que Show Clinic — arranca muteada apenas
     carga la landing (no espera a que cierre el preloader), con un botón de
     mute/unmute siempre visible para que el visitante decida. -->
<audio id="fermentoMusic" src="{{ asset('assets/fermento/audio/fermento-music.mp3') }}" loop muted playsinline preload="auto"></audio>
<button id="fermentoAudioToggle" class="fermento-audio-toggle" type="button" aria-label="Activar música" aria-pressed="false">
    <svg class="icon-muted" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
        <path fill="currentColor" d="M16.5 12A4.5 4.5 0 0 0 14 8v2.18l2.45 2.45c.03-.2.05-.42.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.63l1.51 1.51A8.796 8.796 0 0 0 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3 3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06a8.99 8.99 0 0 0 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4 9.91 6.09 12 8.18V4z"/>
    </svg>
    <svg class="icon-unmuted" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
        <path fill="currentColor" d="M3 9v6h4l5 5V4L7 9H3zm13.5 3A4.5 4.5 0 0 0 14 8v8a4.47 4.47 0 0 0 2.5-4zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
    </svg>
</button>

<script>
    (function () {
        var preloader = document.getElementById('fermentoPreloader');
        if (preloader) {
            document.body.classList.add('fermento-preloading');
            window.addEventListener('load', function () {
                setTimeout(function () {
                    preloader.classList.add('is-hidden');
                    document.body.classList.remove('fermento-preloading');
                }, 900);
            });
        }
    })();

    (function () {
        var music = document.getElementById('fermentoMusic');
        var toggle = document.getElementById('fermentoAudioToggle');
        if (!music || !toggle) return;

        music.play().catch(function () {});

        toggle.addEventListener('click', function () {
            music.muted = !music.muted;
            if (!music.muted) {
                music.play().catch(function () {});
            }
            toggle.classList.toggle('is-unmuted', !music.muted);
            toggle.setAttribute('aria-pressed', String(!music.muted));
            toggle.setAttribute('aria-label', music.muted ? 'Activar música' : 'Silenciar música');
        });
    })();

    (function () {
        var tablesBySchedule = @json($tablesBySchedule);

        var scheduleSelect = document.getElementById('event_schedule_id');
        var grid = document.getElementById('fermentoTablesGrid');
        var emptyMsg = document.getElementById('fermentoTablesEmpty');
        var hint = document.getElementById('fermentoTableHint');
        var tableInput = document.getElementById('event_table_id');
        var partySizeInput = document.getElementById('party_size');

        function renderTables() {
            var scheduleId = scheduleSelect ? scheduleSelect.value : null;
            var tables = scheduleId && tablesBySchedule[scheduleId] ? tablesBySchedule[scheduleId] : null;

            grid.innerHTML = '';
            tableInput.value = '';
            hint.style.display = 'none';

            if (!tables || !tables.length) {
                emptyMsg.style.display = 'block';
                return;
            }
            emptyMsg.style.display = 'none';

            tables.forEach(function (table) {
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'fermento-table-btn';
                btn.disabled = table.is_taken;
                btn.dataset.tableId = table.id;
                btn.dataset.capacityMax = table.capacity_max;
                btn.innerHTML = '<span class="n">Mesa ' + table.table_number + '</span><span class="cap">' + (table.is_taken ? 'Ocupada' : table.capacity_min + '–' + table.capacity_max) + '</span>';

                btn.addEventListener('click', function () {
                    Array.prototype.forEach.call(grid.querySelectorAll('.fermento-table-btn'), function (b) {
                        b.classList.remove('is-selected');
                    });
                    btn.classList.add('is-selected');
                    tableInput.value = table.id;

                    partySizeInput.max = table.capacity_max;
                    if (parseInt(partySizeInput.value || '0', 10) > table.capacity_max) {
                        partySizeInput.value = table.capacity_max;
                    }

                    hint.style.display = 'block';
                    hint.innerHTML = 'Elegiste la <strong>Mesa ' + table.table_number + '</strong> — capacidad de ' + table.capacity_min + ' a ' + table.capacity_max + ' personas.';
                });

                grid.appendChild(btn);
            });
        }

        if (scheduleSelect) {
            scheduleSelect.addEventListener('change', renderTables);
            if (scheduleSelect.value) {
                renderTables();
            }
        }
    })();

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
        gsap.utils.toArray('.fermento-section .anim-title').forEach(function (el) {
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
