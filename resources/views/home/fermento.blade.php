@extends('layout.layout')

@php
    $title = 'Fermento';
    $subTitle = 'Fermento';
    // Cache-buster por fecha de modificación: si se reemplaza un archivo
    // reusando el mismo nombre (como pasó varias veces con las fotos de
    // esta página), la URL cambia sola y el navegador no sirve una copia
    // vieja cacheada.
    $img = function ($name) {
        $path = 'assets/img/fermento/' . $name;
        $full = public_path($path);
        return asset($path) . (file_exists($full) ? '?v=' . filemtime($full) : '');
    };
    $platoImg = fn ($name) => asset('assets/img/' . $name);
@endphp

{{-- Hijo directo de <body> (fuera de #scrollsmoother-container): un ancestro
     con `transform` — como el que aplica GSAP ScrollSmoother sobre ese
     contenedor — convierte a sus descendientes `position: fixed` en fixed
     respecto a ESE ancestro, no al viewport. Por eso el preloader tiene que
     vivir aquí y no dentro de @section('content'). --}}
@section('body-start')
<!-- Preloader: saludo + copy de bienvenida, foto en blanco y negro/oscurecida.
     El botón ENTRAR oculta el preloader y dispara la música (gesto de
     usuario requerido para autoplay con sonido en mobile). -->
<div class="fermento-preloader" id="fermentoPreloader">
    <div class="fermento-preloader-media">
        <img src="{{ $img('preloader-bg.png') }}" alt="" aria-hidden="true">
    </div>
    <div class="fermento-preloader-overlay"></div>
    <div class="fermento-preloader-inner">
        <div class="fermento-eyebrow">MOLTO x FORNO</div>
        <div class="ak-height-20"></div>
        {{-- "Enrique" hardcodeado solo para probar el flujo de punta a
             punta — falta decidir la personalización real (query param,
             dato de la reserva, etc.) antes de publicar. --}}
        <p class="fermento-preloader-greeting">Hola, Enrique</p>
        <p class="fermento-preloader-copy">
            El horno de leña ya está <strong class="w700">encendido</strong> y la masa madre lleva días <strong>fermentando</strong>.
        </p>
        <p class="fermento-preloader-copy">
            Bienvenido a <strong>Fermento</strong> — la noche en que <strong>MOLTO</strong> y <strong>FORNO</strong> se transmutan en una sola mesa.
        </p>
        <button type="button" id="fermentoPreloaderEnter" class="fermento-btn solid fermento-preloader-enter">Entrar</button>
        <p class="fermento-preloader-hint">Haz clic para continuar</p>
    </div>
</div>
@endsection

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
    {{-- No hay un .ttf "ExtraLight" (200) propio de CLAN en el repo — se usa el
         Light (300) del set general del tema, el peso disponible más cercano. --}}
    @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/poppins/poppins-300.woff2') }}') format('woff2'); font-weight: 300; font-style: normal; font-display: swap; }
    {{-- Canela Medium: tipografía de título grande en toda la landing. --}}
    @font-face { font-family: 'Canela'; src: url('{{ asset('assets/fonts/canela/Canela-Medium.ttf') }}') format('truetype'); font-weight: 500; font-style: normal; font-display: swap; }
    {{-- Inter (variable, ejes opsz+wght): copy del preloader y de "Tiempo y
         fuego" / "Menú Degustación". --}}
    @font-face { font-family: 'Inter'; src: url('{{ asset('assets/fonts/inter/Inter-Variable.ttf') }}') format('truetype'); font-weight: 100 900; font-style: normal; font-display: swap; }
    @font-face { font-family: 'Inter'; src: url('{{ asset('assets/fonts/inter/Inter-Italic-Variable.ttf') }}') format('truetype'); font-weight: 100 900; font-style: italic; font-display: swap; }

    /*
     * Paleta Fermento (MOLTO x FORNO).
     */
    :root {
        --body-font-family: 'ClanPoppins', sans-serif;
        --heading-font-family: 'ClanCinzel', serif;
        --title-font-family: 'Canela', 'ClanCinzel', serif;
        --yellow-color: #FBB12F;
        --heading-color: #FBB12F;
        --bronze-color: #A7792A;
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
    /* Títulos de sección del tema (Nuestra cocina / Ambientación / Reserva) */
    .fermento-section .ak-section-title {
        font-family: var(--title-font-family) !important; font-weight: 500;
    }

    /* Logo lockup "MOLTO / Horno Social" — mismo archivo de imagen (sacado
       del Figma) reusado a distintos tamaños en Hero, Nuestra cocina y Footer. */
    .fermento-cocina-logo { height: 64px; width: auto; margin: 0 auto; display: block; }
    .fermento-footer-logo { height: 58px; width: auto; margin: 0 auto; }

    /* Preloader: pantalla completa, foto en B/N oscurecida, gesto de click
       para continuar (necesario para autoplay de audio con sonido en mobile). */
    #preloader { display: none !important; }
    html, body { overflow-x: hidden; }
    .fermento-preloader {
        position: fixed; top: 0; left: 0; z-index: 99999;
        width: 100%; height: 100dvh; overflow: hidden;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        text-align: center;
        transition: opacity .6s ease, visibility .6s ease;
    }
    .fermento-preloader.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    .fermento-preloader-media { position: absolute; inset: 0; width: 100%; height: 100%; z-index: 1; }
    .fermento-preloader-media img {
        width: 100%; height: 100%; object-fit: cover; object-position: center 40%;
        filter: brightness(.68); display: block;
    }
    .fermento-preloader-overlay { position: absolute; inset: 0; width: 100%; height: 100%; z-index: 2; background: rgba(10,8,6,.35); }
    .fermento-preloader-inner { position: relative; z-index: 3; padding: 24px; max-width: 640px; }
    .fermento-preloader-greeting {
        font-family: var(--title-font-family); font-weight: 500; color: var(--yellow-color);
        font-size: clamp(36px, 8vw, 64px); line-height: 1.1; margin: 0 0 20px;
    }
    .fermento-preloader-copy {
        font-family: 'Inter', var(--body-font-family); font-weight: 300; font-style: italic; color: var(--common-color-white);
        font-size: clamp(15px, 2.1vw, 21px); line-height: 1.6; margin: 0 0 14px;
    }
    .fermento-preloader-copy strong { font-weight: 600; font-style: italic; }
    .fermento-preloader-copy strong.w700 { font-weight: 700; }
    .fermento-preloader-enter { min-width: 180px; min-height: 44px; justify-content: center; margin-top: 18px; }
    .fermento-preloader-hint {
        font-family: var(--body-font-family); color: var(--common-color-white); opacity: .7;
        font-size: 11px; letter-spacing: .04em; margin: 18px 0 0;
    }
    body.fermento-preloading { overflow: hidden; }
    /* Segunda red de seguridad: mientras el preloader está activo, oculta por
       completo el contenido real detrás para que no pueda "fantasmear". */
    body.fermento-preloading #scrollsmoother-container { visibility: hidden; }

    /* Hero: foto full-bleed (ya viene con el lado derecho oscuro/vacío en la
       foto misma). Título+subtítulo+cita en columna desde ~64% del ancho;
       botones abajo-izquierda, lockups de marca abajo-derecha. */
    .fermento-hero { position: relative; min-height: 100vh; overflow: hidden; }
    .fermento-hero-media { position: absolute; inset: 0; z-index: 0; }
    .fermento-hero-media img { width: 100%; height: 100%; object-fit: cover; object-position: center 20%; display: block; }
    .fermento-hero-content { position: relative; z-index: 1; min-height: 100vh; display: flex; align-items: center; }
    .fermento-hero-textcol { margin-left: 54%; width: 46%; box-sizing: border-box; padding: 0 6% 0 0; text-align: center; }
    .fermento-hero-textcol h1 {
        font-family: var(--title-font-family); font-weight: 500; text-align: left;
        color: #FBB12F; font-size: clamp(52px, 7.5vw, 110px);
        line-height: 1.02; margin: 0 0 18px;
        text-shadow: 0 4px 26px rgba(0,0,0,.55);
    }
    .fermento-hero-subtitle {
        font-family: var(--body-font-family); font-weight: 400; text-transform: uppercase;
        color: var(--common-color-white); font-size: 13px; letter-spacing: .2em; margin: 0 0 22px;
    }
    .fermento-hero-quote {
        font-family: var(--body-font-family); font-weight: 300; font-style: italic;
        color: #F6EEE1; font-size: 21px; line-height: 1.5; margin: 0;
    }
    .fermento-hero-bottom {
        position: absolute; left: 0; right: 0; bottom: 56px; z-index: 2;
        display: flex; align-items: flex-end; justify-content: space-between;
        padding: 0 6%; gap: 24px; flex-wrap: wrap;
    }
    .fermento-hero-ctas { display: flex; gap: 18px; flex-wrap: wrap; margin-left: 31%; }
    .fermento-hero-logos { display: flex; align-items: center; gap: 28px; }
    .fermento-hero-logos img { display: block; height: 50px; width: auto; }
    .fermento-hero-logos img.forno { height: 54px; }
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

    /* Secciones de ancho completo con foto de fondo real (Tiempo y fuego,
       Enrique Mendoza) — foto + degradado + contenido en texto claro. */
    .fermento-feature { position: relative; overflow: hidden; }
    .fermento-feature-media { position: absolute; inset: 0; z-index: 0; }
    .fermento-feature-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .fermento-feature-overlay {
        position: absolute; inset: 0; z-index: 1;
        background: linear-gradient(90deg, rgba(26,20,16,.94) 0%, rgba(26,20,16,.82) 38%, rgba(26,20,16,.4) 72%, rgba(26,20,16,.15) 100%);
    }
    .fermento-feature-inner { position: relative; z-index: 2; max-width: 620px; padding-top: 110px; padding-bottom: 110px; }
    .fermento-feature-body { color: var(--common-color-white); font-size: 16px; line-height: 1.9; opacity: .95; margin: 0 0 20px; }
    .fermento-feature-body:last-child { margin-bottom: 0; }

    /* Tiempo y fuego — foto con brillo completo (sin oscurecer arriba, donde
       están los chefs). Antecedente + "TIEMPO Y FUEGO" pegados arriba; el
       párrafo va aparte, pegado abajo, sobre el degradado suave/moderado. */
    .fermento-feature--tiempo { min-height: 100vh; }
    .fermento-feature-overlay--soft {
        background: linear-gradient(180deg, rgba(26,20,15,0) 0%, rgba(26,20,15,0) 60%, rgba(26,20,15,.25) 80%, rgba(26,20,15,.55) 100%);
    }
    /* Posiciones absolutas por % exacto, calcadas del Figma (frame de
       1200px de alto): kicker ~3%, título ~7% (pegado, mismo bloque),
       párrafo ~79% — un bloque arriba del todo y otro aparte, casi abajo. */
    .fermento-feature-top {
        position: absolute; top: 3%; left: 50%; transform: translateX(-50%);
        z-index: 2; width: 85%; text-align: center;
    }
    .fermento-feature-bottom {
        position: absolute; top: 79%; left: 50%; transform: translateX(-50%);
        z-index: 2; width: 74%; text-align: center;
    }
    .fermento-feature-kicker {
        font-family: 'Inter', var(--body-font-family); font-weight: 400; color: var(--common-color-white);
        font-size: clamp(14px, 1.35vw, 24px); margin: 0 0 8px; text-shadow: 0 2px 12px rgba(0,0,0,.4);
    }
    .fermento-feature-kicker strong { font-weight: 700; }
    .fermento-feature-title-lg {
        font-family: var(--title-font-family); font-weight: 500; text-transform: uppercase;
        color: var(--yellow-color); font-size: clamp(30px, 4vw, 76px); line-height: 1.05; margin: 0;
        text-shadow: 0 4px 26px rgba(0,0,0,.5);
    }
    .fermento-feature-bottom p {
        font-family: 'Inter', var(--body-font-family); font-weight: 400; color: var(--common-color-white);
        font-size: clamp(12px, 1.15vw, 21px); line-height: 1.55; margin: 0 0 12px;
        text-shadow: 0 2px 14px rgba(0,0,0,.6);
    }
    .fermento-feature-bottom p:last-child { margin-bottom: 0; }
    .fermento-feature-bottom strong { font-weight: 700; }
    .fermento-feature-bottom strong.w800 { font-weight: 800; }

    /* Enrique Mendoza — columna a la izquierda, título más chico (90px) en
       bronce, subtítulo "Forno Chef's Table", 3 párrafos ExtraLight. */
    .fermento-feature--enrique h2 {
        font-family: var(--title-font-family); font-weight: 500; color: var(--bronze-color);
        font-size: clamp(38px, 6vw, 90px); line-height: 1.05; margin: 0 0 18px;
    }
    .fermento-feature-tag {
        font-family: var(--body-font-family); font-weight: 400;
        color: var(--common-color-white); font-size: 24px; margin: 0 0 30px;
    }
    .fermento-feature--enrique .fermento-feature-body {
        font-family: var(--body-font-family); font-weight: 300; color: var(--common-color-white);
        font-size: 18px; line-height: 2;
    }
    .fermento-feature-inner--enrique { padding-left: 24px; padding-right: 24px; }
    @media (min-width: 768px) {
        .fermento-feature-inner--enrique { width: 40%; max-width: 640px; padding-left: 5.2%; padding-right: 24px; }
        .fermento-feature--enrique .fermento-feature-media img { transform: scale(1.04); transform-origin: center right; }
        .fermento-feature--enrique .fermento-feature-overlay { opacity: .6; }
    }

    /* Menú Degustación — dos columnas: foto a la izquierda, carta a la
       texto sobre el lado claro de la foto — foto full-bleed continua, NO
       dos paneles separados, columna centrada en ~72.5% del ancho. */
    .fermento-feature--menu { position: relative; overflow: hidden; aspect-ratio: 1920 / 1152; }
    .fermento-feature--menu .fermento-feature-media img { object-fit: cover; }
    .fermento-menu-content {
        position: absolute; top: 50%; left: 72.5%; transform: translate(-50%, -50%);
        z-index: 1; width: clamp(300px, 45vw, 860px); max-width: 90%; text-align: center;
    }
    .fermento-menu-label {
        font-family: var(--body-font-family); font-weight: 400; color: #000;
        font-size: clamp(14px, 1.25vw, 24px); margin: 0 0 4px;
    }
    .fermento-menu-title {
        font-family: var(--title-font-family); font-weight: 500; color: var(--bronze-color);
        font-size: clamp(38px, 5.7vw, 110px); line-height: 1; margin: 0 0 34px;
    }
    .fermento-tempos-list { list-style: none; padding: 0; margin: 0; }
    .fermento-tempos-list li {
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; padding: 10px 0;
    }
    .fermento-tempos-list .num {
        font-family: var(--heading-font-family); font-weight: 700; color: var(--bronze-color);
        flex: none; font-size: clamp(13px, 1.15vw, 22px); letter-spacing: .08em;
    }
    .fermento-tempos-list .dish {
        font-family: 'Inter', var(--body-font-family); font-weight: 400; color: #000; font-size: clamp(12px, 1.05vw, 20px); line-height: 1.5;
    }

    /* Nuestra cocina */
    .fermento-kitchen-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 26px; }
    .fermento-kitchen-photo { overflow: hidden; border: 1px solid var(--border-color); }
    .fermento-kitchen-photo img {
        width: 100%; aspect-ratio: 3 / 4; height: auto; object-fit: cover;
        filter: grayscale(8%); transition: filter .5s ease, transform .5s ease;
    }
    .fermento-kitchen-photo:hover img { filter: grayscale(0%); transform: scale(1.04); }
    .fermento-kitchen-photo.is-crop-bottom img { transform-origin: center top; transform: scale(1.12); }
    .fermento-kitchen-photo.is-crop-bottom:hover img { transform: scale(1.18); }

    /* Precio */
    .fermento-price-card {
        border: 1px solid var(--yellow-color); padding: 50px 40px; text-align: center; max-width: 480px; margin: 0 auto;
        background: #302213;
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
    .fermento-alert-error { border: 1px solid #b95a4a; color: #f3c9c1; padding: 16px 20px; margin-bottom: 26px; font-size: 14px; }
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
    .fermento-footer-inner { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 22px; }
    .fermento-footer .line { color: var(--common-color-white); font-size: 14px; }
    .fermento-footer .line a { color: var(--common-color-white); text-decoration: none; border-bottom: 1px solid var(--border-color); }
    .fermento-footer .line a:hover { color: var(--yellow-color); border-color: var(--yellow-color); }
    .fermento-social--text { display: flex; gap: 20px; }
    .fermento-social--text a {
        font-family: var(--body-font-family); color: var(--yellow-color); font-size: 14px; text-decoration: none;
    }
    .fermento-social--text a:hover { opacity: .75; }
    .fermento-copy { color: #6b6055; font-size: 12px; margin-top: 6px; }

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

    @media (max-width: 991px) {
        .fermento-kitchen-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 767px) {
        .fermento-audio-toggle { bottom: 1.25rem; right: 1.25rem; width: 44px; height: 44px; }

        /* Hero mobile: una sola columna — foto arriba, texto centrado abajo. */
        .fermento-hero { min-height: auto; }
        .fermento-hero-media { position: relative; inset: auto; height: 46vh; min-height: 320px; }
        .fermento-hero-media img { object-position: 18% center; }
        .fermento-hero-content { position: static; min-height: auto; display: block; background: var(--body-bg-color); padding: 44px 24px 24px; }
        .fermento-hero-textcol { margin-left: 0; width: 100%; padding: 0; text-align: center; }
        .fermento-hero-textcol h1 { font-size: clamp(40px, 13vw, 56px); }
        .fermento-hero-quote { text-align: center; }
        .fermento-hero-bottom {
            position: static; padding: 0 24px 40px; background: var(--body-bg-color);
            flex-direction: column; align-items: center; gap: 26px;
        }
        .fermento-hero-ctas { justify-content: center; }
        .fermento-hero-logos { justify-content: center; }

        .fermento-preloader-media img { object-position: 62% center; }

        .fermento-feature-overlay { background: linear-gradient(180deg, rgba(26,20,16,.55) 0%, rgba(26,20,16,.88) 55%, rgba(26,20,16,.96) 100%); }
        .fermento-feature-inner { padding-top: 60px; padding-bottom: 60px; max-width: 100%; }

        /* Tiempo y fuego mobile */
        .fermento-feature--tiempo { min-height: 88vh; }
        .fermento-feature--tiempo .fermento-feature-overlay {
            background: linear-gradient(180deg, rgba(26,20,15,0) 0%, rgba(26,20,15,.1) 45%, rgba(26,20,15,.5) 75%, rgba(26,20,15,.7) 100%);
        }
        .fermento-feature-top, .fermento-feature-bottom { width: 90%; }

        /* Menú Degustación mobile: la foto ya no garantiza que la pared clara
           quede debajo del texto en todos los recortes — se agrega un fondo
           claro detrás del bloque para legibilidad. */
        .fermento-feature--menu { aspect-ratio: auto; min-height: 700px; }
        .fermento-menu-content {
            left: 50%; width: 90%; max-width: 420px;
            background: rgba(232,226,216,.92); padding: 28px 22px; border-radius: 4px;
        }

        .fermento-field-row { grid-template-columns: 1fr; }
        .fermento-kitchen-grid { grid-template-columns: 1fr; max-width: 360px; margin: 0 auto; }
        .fermento-tables-grid { grid-template-columns: repeat(3, 1fr); }
        .fermento-tables-legend { gap: 14px; }
    }
</style>

<!-- Hero -->
<section class="fermento-hero">
    <div class="fermento-hero-media">
        <img src="{{ $img('hero-chefs.jpg') }}" alt="Chefs de MOLTO y FORNO">
    </div>
    <div class="fermento-hero-content">
        <div class="fermento-hero-textcol">
            <h1>{{ strtoupper($event->name) }}</h1>
            <p class="fermento-hero-subtitle">Transmutación de la masa madre</p>
            <p class="fermento-hero-quote">"Una noche de masa madre, fuego de leña y vino."</p>
        </div>
    </div>
    <div class="fermento-hero-bottom">
        <div class="fermento-hero-ctas">
            <a href="#reservar" class="fermento-btn solid">Reservar mi mesa</a>
            <a href="#concepto" class="fermento-btn">Conocer el concepto</a>
        </div>
        <div class="fermento-hero-logos">
            <img src="{{ $img('logos/molto-logo.png') }}" alt="MOLTO Horno Social">
            <img src="{{ $img('logos/forno-hero.png') }}" alt="Forno Chef's Table" class="forno">
        </div>
    </div>
</section>

<!-- Tiempo y fuego -->
<section id="concepto" class="fermento-feature fermento-feature--tiempo">
    <div class="fermento-feature-media">
        <img src="{{ $img('tiempo-fuego.jpg') }}" alt="Los chefs de MOLTO y FORNO junto al fuego — Fermento">
    </div>
    <div class="fermento-feature-overlay fermento-feature-overlay--soft"></div>
    <div class="fermento-feature-top">
        <p class="fermento-feature-kicker">Todo lo que <strong>fermenta</strong> empieza igual:</p>
        <h2 class="fermento-feature-title-lg">Tiempo y fuego</h2>
    </div>
    <div class="fermento-feature-bottom">
        <p>
            <strong>Fermento</strong> es el encuentro entre <strong class="w800">MOLTO</strong> y <strong>FORNO</strong> alrededor
            de dos procesos que empiezan igual: tiempo, calor y <strong>paciencia</strong>.
        </p>
        <p>
            Una noche comunitaria bajo carpa, con <strong>pizzas</strong> al horno de <strong>leña</strong>, platos
            de autor de <strong>MOLTO</strong> y una selección de vinos pensada para acompañar ambas cocinas en la
            misma mesa.
        </p>
    </div>
</section>

<!-- Menú Degustación -->
<section class="fermento-feature fermento-feature--menu">
    <div class="fermento-feature-media">
        <img src="{{ $img('menu-degustacion.png') }}" alt="Los chefs de MOLTO y FORNO — Menú Degustación Fermento">
    </div>
    <div class="fermento-menu-content">
        <p class="fermento-menu-label">MENU DEGUSTACIÓN</p>
        <h2 class="fermento-menu-title">FERMENTO</h2>
        <ul class="fermento-tempos-list">
            <li><span class="num">I TEMPO</span><span class="dish">Pesto con hierbas del andes</span></li>
            <li><span class="num">II TEMPO</span><span class="dish">Higo fresco relleno de ruda de cabra, prosciutto, miel y reducción balsámica</span></li>
            <li><span class="num">III TEMPO</span><span class="dish">Pan de hongos, zapallo y queso de Lluta</span></li>
            <li><span class="num">IV TEMPO</span><span class="dish">Hot honey Cheesecake</span></li>
            <li><span class="num">V TEMPO</span><span class="dish">Piazza de truta y tumbo al Pesto</span></li>
            <li><span class="num">VI TEMPO</span><span class="dish">Pizza French onion soup</span></li>
            <li><span class="num">VII TEMPO</span><span class="dish">Pizza Fuoco e Miele</span></li>
            <li><span class="num">VIII TEMPO</span><span class="dish">Cremoso de piña con hongos braseados</span></li>
            <li><span class="num">IX TEMPO</span><span class="dish">Trufa de cioccolato con tubérculos andinos</span></li>
        </ul>
    </div>
</section>

<!-- Enrique Mendoza -->
<section class="fermento-feature fermento-feature--enrique">
    <div class="fermento-feature-media">
        <img src="{{ $img('enrique-mendoza.png') }}" alt="Enrique Mendoza, chef de Forno Chef's Table, junto al horno de leña">
    </div>
    <div class="fermento-feature-overlay"></div>
    <div class="fermento-feature-inner fermento-feature-inner--enrique">
        <h2>Enrique Mendoza</h2>
        <p class="fermento-feature-tag">Forno Chef's Table</p>
        <p class="fermento-feature-body">
            Al otro lado del horno de leña está Enrique Mendoza, chef de Forno Chef's Table — un espacio donde la
            pizza se entiende como oficio, no como fórmula.
        </p>
        <p class="fermento-feature-body">
            Su cocina parte de una idea simple: el fuego y el tiempo son los únicos ingredientes que no se pueden
            apurar.
        </p>
        <p class="fermento-feature-body">
            Para Fermento, Enrique se sienta a la misma mesa que Mauricio Mello. Dos cocinas, un mismo origen: la
            fermentación como punto de partida, la transformación como destino.
        </p>
    </div>
</section>

<!-- Nuestra cocina -->
<section class="fermento-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="fermento-eyebrow">Nuestra cocina</div>
            <div class="ak-height-25"></div>
            <img src="{{ $img('logos/molto-logo.png') }}" alt="MOLTO Horno Social" class="fermento-cocina-logo">
        </div>
        <div class="ak-height-60 ak-height-lg-30"></div>
        <div class="fermento-kitchen-grid">
            @foreach (['cocina-1.png', 'cocina-2.png', 'cocina-3.png', 'cocina-4.png'] as $foto)
                <div class="fermento-kitchen-photo">
                    <img src="{{ $img($foto) }}" alt="MOLTO Horno Social">
                </div>
            @endforeach
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Ambientación -->
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
                <li>Pizzas al horno de leña y platos de autor de MOLTO</li>
                <li>Vinos seleccionados para acompañar la mesa</li>
                <li>Mesa comunitaria bajo servicio a lo largo de la noche</li>
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
            <div class="fermento-eyebrow">Reserva tu mesa</div>
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
        <img src="{{ $img('logos/molto-logo.png') }}" alt="MOLTO Horno Social" class="fermento-footer-logo">

        <div class="line">
            Reservas al <a href="tel:+51941486154">941 486 154</a>
        </div>

        {{-- Texto literal del Figma: "📍Psj. Violin..." sin espacio después
             del pin y "Violin" sin tilde — no "corregir" sin confirmar. --}}
        <div class="line">
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Psj. Violin 101 F San Lázaro (Plaza Campo Redondo) Arequipa') }}" target="_blank" rel="noopener">
                📍Psj. Violin 101 F San Lázaro (Plaza Campo Redondo) - Arequipa
            </a>
        </div>

        {{-- Pendiente: confirmar los handles reales de MOLTO — estos links no
             deben apuntar a las redes de CLAN. Sin href hasta tener la URL real. --}}
        <div class="fermento-social fermento-social--text">
            <a href="#" onclick="return false;">[ facebook ]</a>
            <a href="#" onclick="return false;">[ instagram ]</a>
        </div>

        <div class="fermento-copy">CLAN · Hambre de Crear</div>
    </div>
</footer>

<!-- Música de fondo: arranca (sin mute) recién cuando el visitante hace clic
     en "Entrar" del preloader. El botón de mute/unmute recuerda la
     preferencia del visitante en localStorage. -->
<audio id="fermentoMusic" src="{{ asset('audio/fermento-bg.mp3') }}" loop playsinline preload="auto"></audio>
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
        var enterBtn = document.getElementById('fermentoPreloaderEnter');
        var music = document.getElementById('fermentoMusic');
        var toggle = document.getElementById('fermentoAudioToggle');
        if (!preloader) return;

        document.body.classList.add('fermento-preloading');

        var STORAGE_KEY = 'fermentoMusicMuted';
        var storedMuted = null;
        try { storedMuted = window.localStorage.getItem(STORAGE_KEY); } catch (e) {}
        var startMuted = storedMuted === '1';

        function setMuted(muted) {
            if (!music || !toggle) return;
            music.muted = muted;
            toggle.classList.toggle('is-unmuted', !muted);
            toggle.setAttribute('aria-pressed', String(!muted));
            toggle.setAttribute('aria-label', muted ? 'Activar música' : 'Silenciar música');
            try { window.localStorage.setItem(STORAGE_KEY, muted ? '1' : '0'); } catch (e) {}
        }

        function enter() {
            preloader.classList.add('is-hidden');
            document.body.classList.remove('fermento-preloading');

            if (music) {
                music.volume = 0.45;
                setMuted(startMuted);
                music.play().catch(function () {});
            }
        }

        if (enterBtn) {
            enterBtn.addEventListener('click', enter);
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                if (!music) return;
                setMuted(!music.muted);
                if (!music.muted) {
                    music.play().catch(function () {});
                }
            });
        }
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
