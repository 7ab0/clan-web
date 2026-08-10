@extends('layout.layout')

@php
    $title = 'Íntimo';
    $subTitle = 'Íntimo';
    $img = fn ($name) => asset('assets/img/intimo/' . $name);
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

    /* ===== Paleta CLAN: negro, grises, blanco, dorado — solo para esta página ===== */
    :root {
        --body-font-family: 'ClanPoppins', sans-serif;
        --heading-font-family: 'ClanCinzel', serif;
        --yellow-color: #C9A961;
        --heading-color: #C9A961;
        --body-color: #B9B9B9;
        --body-bg-color: #0A0A0A;
        --body-bg-color-two: #161616;
        --border-color: #333333;
        --common-color-white: #FFFFFF;
    }

    /* ===== Íntimo — estilos propios de esta página ===== */
    .intimo-section { position: relative; }
    .intimo-eyebrow {
        font-family: var(--body-font-family);
        letter-spacing: .28em;
        text-transform: uppercase;
        font-size: 13px;
        color: var(--yellow-color);
        opacity: .9;
    }
    .intimo-logo { max-width: 130px; height: auto; margin-bottom: 22px; opacity: .95; }

    .intimo-hero {
        position: relative;
        min-height: 92vh;
        display: flex;
        align-items: flex-end;
        background: linear-gradient(180deg, rgba(10,10,10,.35) 0%, rgba(10,10,10,.6) 45%, rgba(10,10,10,.97) 100%), url('{{ $img('hero-atardecer.jpg') }}') center 30%/cover no-repeat;
    }
    .intimo-hero-inner { padding: 0 0 80px; max-width: 760px; }
    .intimo-hero h1 {
        font-family: var(--heading-font-family);
        color: var(--heading-color);
        font-size: clamp(48px, 7vw, 92px);
        line-height: 1.05;
        margin: 18px 0 22px;
    }
    .intimo-hero p.tagline {
        font-family: var(--body-font-family);
        font-style: italic;
        font-weight: 300;
        color: var(--common-color-white);
        font-size: clamp(18px, 2vw, 23px);
        max-width: 560px;
        opacity: .92;
    }
    .intimo-hero-ctas { display: flex; gap: 18px; margin-top: 40px; flex-wrap: wrap; }
    .intimo-btn {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 16px 30px;
        border: 1px solid var(--yellow-color);
        color: var(--yellow-color);
        text-transform: uppercase; letter-spacing: .14em; font-size: 13px;
        transition: all .3s ease;
        font-family: var(--body-font-family);
        cursor: pointer;
    }
    .intimo-btn.solid { background: var(--yellow-color); color: #0A0A0A; }
    .intimo-btn:hover { background: var(--yellow-color); color: #0A0A0A; }

    .intimo-narrow { max-width: 720px; margin: 0 auto; text-align: center; }
    .intimo-story-grid {
        max-width: 1040px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; text-align: left;
    }
    .intimo-story-grid img { width: 100%; height: 520px; object-fit: cover; filter: grayscale(15%); }
    .intimo-story p { color: var(--body-color); font-size: 16px; line-height: 1.9; }
    .intimo-quote {
        font-family: var(--heading-font-family);
        font-size: clamp(22px, 2.6vw, 30px);
        color: var(--heading-color);
        line-height: 1.5;
        margin: 0 0 26px;
    }

    .intimo-mechanic { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; }
    .intimo-mechanic-item { text-align: center; border-top: 1px solid var(--border-color); padding-top: 22px; }
    .intimo-mechanic-item .num { color: var(--yellow-color); font-family: var(--heading-font-family); font-size: 20px; }
    .intimo-mechanic-item h5 { color: var(--heading-color); margin: 10px 0 8px; font-size: 18px; }
    .intimo-mechanic-item p { color: var(--body-color); font-size: 14px; line-height: 1.7; }

    .intimo-book-gallery { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; max-width: 900px; margin: 0 auto; }
    .intimo-book-gallery img { width: 100%; height: 340px; object-fit: cover; border: 1px solid var(--border-color); }

    /* Chefs invitados */
    .intimo-chefs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; }
    .intimo-chef-card { text-align: center; }
    .intimo-chef-photo { overflow: hidden; border: 1px solid var(--border-color); margin-bottom: 22px; }
    .intimo-chef-photo img {
        width: 100%; height: 380px; object-fit: cover;
        filter: grayscale(100%);
        transition: filter .5s ease, transform .5s ease;
    }
    .intimo-chef-card:hover .intimo-chef-photo img { filter: grayscale(0%); transform: scale(1.03); }
    .intimo-chef-card .tag {
        display: inline-block; color: var(--yellow-color); font-size: 12px; letter-spacing: .12em; text-transform: uppercase; margin-bottom: 10px;
    }
    .intimo-chef-card p { color: var(--body-color); font-size: 14px; line-height: 1.8; }

    /* Menú */
    .intimo-menu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 34px; }
    .intimo-course-card { border: 1px solid var(--border-color); }
    .intimo-course-card .photo-wrap { position: relative; }
    .intimo-course-card img { width: 100%; height: 320px; object-fit: cover; display: block; filter: grayscale(10%); }
    .intimo-course-card .num-badge {
        position: absolute; top: 16px; left: 16px; width: 42px; height: 42px; border: 1px solid var(--yellow-color);
        background: rgba(10,10,10,.75); color: var(--yellow-color); font-family: var(--heading-font-family);
        display: flex; align-items: center; justify-content: center; font-size: 16px;
    }
    .intimo-course-card .info { padding: 24px 26px 28px; }
    .intimo-course-card h5 { color: var(--heading-color); font-size: 21px; margin-bottom: 10px; }
    .intimo-course-card p { color: var(--body-color); font-size: 14px; margin: 0; line-height: 1.7; }

    .intimo-tags { display: flex; flex-wrap: wrap; gap: 14px; justify-content: center; }
    .intimo-tag {
        border: 1px solid var(--border-color); color: var(--body-color);
        padding: 10px 22px; font-size: 14px; letter-spacing: .04em;
    }

    .intimo-video-wrap { position: relative; border: 1px solid var(--border-color); }
    .intimo-video-wrap img { width: 100%; display: block; opacity: .5; filter: grayscale(30%); }
    .intimo-video-play {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 14px;
    }
    .intimo-video-play .circle {
        width: 84px; height: 84px; border-radius: 50%; border: 1px solid var(--yellow-color);
        display: flex; align-items: center; justify-content: center;
    }
    .intimo-video-play span.label { color: var(--common-color-white); font-family: var(--body-font-family); font-style: italic; font-size: 16px; }

    .intimo-gallery { display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px; }
    .intimo-gallery img { width: 100%; height: 280px; object-fit: cover; display: block; filter: grayscale(10%); transition: transform .5s ease, filter .5s ease; }
    .intimo-gallery a:hover img { transform: scale(1.04); filter: grayscale(0%); }

    .intimo-price-card {
        border: 1px solid var(--yellow-color); padding: 50px 40px; text-align: center; max-width: 480px; margin: 0 auto;
        background: var(--body-bg-color-two);
    }
    .intimo-price-card .amount { font-family: var(--heading-font-family); color: var(--heading-color); font-size: 52px; }
    .intimo-price-card .per { color: var(--body-color); font-size: 13px; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 26px; }
    .intimo-price-card ul { list-style: none; padding: 0; margin: 0 0 30px; text-align: left; }
    .intimo-price-card ul li { color: var(--body-color); padding: 8px 0; border-bottom: 1px dashed var(--border-color); font-size: 14px; }
    .intimo-price-card ul li:last-child { border-bottom: none; }

    .intimo-event-meta { text-align: center; color: var(--body-color); font-size: 15px; margin-top: 30px; }
    .intimo-event-meta strong { color: var(--heading-color); }

    .intimo-book-form {
        max-width: 640px; margin: 0 auto; background: var(--body-bg-color-two); border: 1px solid var(--border-color); padding: 46px;
    }
    .intimo-field { margin-bottom: 22px; }
    .intimo-field label { display: block; color: var(--heading-color); font-size: 13px; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 10px; }
    .intimo-field input, .intimo-field select, .intimo-field textarea {
        width: 100%; background: transparent; border: 1px solid var(--border-color); color: var(--common-color-white);
        padding: 14px 16px; font-family: var(--body-font-family); font-size: 15px;
    }
    .intimo-field input:focus, .intimo-field select:focus, .intimo-field textarea:focus { outline: none; border-color: var(--yellow-color); }
    .intimo-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .intimo-alert-error {
        border: 1px solid #b95a4a; color: #f3c9c1; padding: 16px 20px; margin-bottom: 26px; font-size: 14px;
    }
    .intimo-alert-error ul { margin: 0; padding-left: 18px; }
    .intimo-note { color: var(--body-color); font-size: 13px; margin-top: 10px; opacity: .8; }

    /* Footer propio */
    .intimo-footer { background: #050505; border-top: 1px solid var(--border-color); padding: 70px 0 40px; }
    .intimo-footer-inner { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 24px; }
    .intimo-footer img.icon { width: 64px; height: auto; }
    .intimo-footer .line { color: var(--body-color); font-size: 15px; }
    .intimo-footer .line a { color: var(--body-color); text-decoration: none; border-bottom: 1px solid var(--border-color); }
    .intimo-footer .line a:hover { color: var(--yellow-color); border-color: var(--yellow-color); }
    .intimo-social { display: flex; gap: 20px; }
    .intimo-social a {
        width: 42px; height: 42px; border: 1px solid var(--border-color); border-radius: 50%;
        display: flex; align-items: center; justify-content: center; color: var(--body-color); transition: all .3s ease;
    }
    .intimo-social a:hover { border-color: var(--yellow-color); color: var(--yellow-color); }
    .intimo-copy { color: #555; font-size: 12px; margin-top: 20px; }

    /* Bienvenida personalizada */
    .intimo-welcome {
        position: fixed; inset: 0; z-index: 9999; background: #0A0A0A;
        display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 24px;
        text-align: center; padding: 30px; cursor: pointer;
        transition: opacity .8s ease, visibility .8s ease;
    }
    .intimo-welcome.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    .intimo-welcome .eyebrow {
        font-family: var(--body-font-family); letter-spacing: .3em; text-transform: uppercase;
        font-size: 12px; color: var(--yellow-color); opacity: .85;
    }
    .intimo-welcome h1 {
        font-family: var(--heading-font-family); color: var(--heading-color);
        font-size: clamp(38px, 7vw, 68px); margin: 0; line-height: 1.15;
    }
    .intimo-welcome p {
        color: var(--body-color); font-size: 15px; max-width: 420px; line-height: 1.8;
    }
    .intimo-welcome .enter-hint {
        color: var(--yellow-color); font-size: 12px; letter-spacing: .2em; text-transform: uppercase;
        border: 1px solid var(--yellow-color); padding: 14px 28px; margin-top: 10px;
    }
    body.intimo-locked { overflow: hidden; }

    @media (max-width: 767px) {
        .intimo-mechanic { grid-template-columns: 1fr 1fr; }
        .intimo-gallery { grid-template-columns: 1fr 1fr; }
        .intimo-field-row { grid-template-columns: 1fr; }
        .intimo-story-grid { grid-template-columns: 1fr; gap: 30px; text-align: center; }
        .intimo-story-grid img { height: 320px; }
        .intimo-book-gallery { grid-template-columns: 1fr; }
        .intimo-chefs { grid-template-columns: 1fr; }
        .intimo-menu-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Bienvenida personalizada -->
@if ($guest)
    <div class="intimo-welcome" id="intimoWelcome">
        <div class="eyebrow">Un ritual del universo CLAN</div>
        <h1>Kamisaraki, {{ $guest->first_name }}</h1>
        <p>
            Antes de existir un restaurante, existió una mesa.
            Esta invitación es solo tuya — entra cuando estés listo para descubrir Íntimo.
        </p>
        <div class="enter-hint">Toca para entrar</div>
    </div>
    <script>
        (function () {
            document.body.classList.add('intimo-locked');
            var welcome = document.getElementById('intimoWelcome');
            function enter() {
                welcome.classList.add('is-hidden');
                document.body.classList.remove('intimo-locked');
                welcome.removeEventListener('click', enter);
            }
            welcome.addEventListener('click', enter);
        })();
    </script>
@endif

<!-- Hero -->
<section class="intimo-hero">
    <div class="container intimo-hero-inner">
        <img src="{{ $img('clan-logo.png') }}" alt="CLAN" class="intimo-logo">
        <div class="intimo-eyebrow">Un ritual del universo CLAN</div>
        <h1>{{ $event->name }}</h1>
        <p class="tagline">"{{ $event->tagline }}"</p>
        <div class="intimo-hero-ctas">
            <a href="#reservar" class="intimo-btn solid">Reservar mi mesa</a>
            <a href="#historia" class="intimo-btn">Conocer el ritual</a>
        </div>
    </div>
</section>

<!-- La historia -->
<section id="historia" class="intimo-section">
    <div class="ak-height-150 ak-height-lg-70"></div>
    <div class="container">
        <div class="intimo-story-grid">
            <div>
                <div class="intimo-eyebrow">La historia</div>
                <div class="ak-height-20"></div>
                <p class="intimo-quote">El hollín no es suciedad. Es memoria.</p>
                <div class="intimo-story">
                    <p>{{ $event->description }}</p>
                    <p>
                        Cada plato funciona como un capítulo de una conversación que solo ocurre una vez.
                        No importa si llegas con tu pareja, un amigo, un hermano o tu madre: Íntimo es una
                        invitación a sentarse alrededor del fogón, donde cada plato rescata una historia
                        y cada pregunta ayuda a descubrir algo del otro.
                    </p>
                </div>
            </div>
            <img src="{{ $img('hollin-intimo.jpg') }}" alt="El hollín de Íntimo — CLAN">
        </div>
    </div>
    <div class="ak-height-150 ak-height-lg-70"></div>
</section>

<!-- El libro / mecánica -->
<section class="intimo-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">El libro</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title" style="max-width:640px;margin:0 auto;">
                Un libro que se lee entre plato y plato
            </h2>
        </div>
        <div class="ak-height-30"></div>
        <div class="container intimo-narrow">
            <p style="color:var(--body-color);font-size:16px;line-height:1.9;">
                La experiencia incluye un libro propio de Íntimo. No es un souvenir: es parte del
                recorrido. Cada capítulo trae un acertijo, una pregunta o una reflexión que se va
                resolviendo conforme llegan los platos. La comida se convierte en la respuesta.
            </p>
        </div>
        <div class="ak-height-50 ak-height-lg-30"></div>
        <div class="intimo-book-gallery">
            <img src="{{ $img('libro-1.jpg') }}" alt="Libro Hambre de Crear — CLAN">
            <img src="{{ $img('libro-2.jpg') }}" alt="Página interior del libro — CLAN">
        </div>
        <div class="ak-height-60 ak-height-lg-30"></div>
        <div class="intimo-mechanic">
            <div class="intimo-mechanic-item">
                <div class="num">01</div>
                <h5>Libro</h5>
                <p>Abren un capítulo nuevo en la mesa.</p>
            </div>
            <div class="intimo-mechanic-item">
                <div class="num">02</div>
                <h5>Pregunta</h5>
                <p>Un acertijo o reflexión que los invita a hablar.</p>
            </div>
            <div class="intimo-mechanic-item">
                <div class="num">03</div>
                <h5>Plato</h5>
                <p>La cocina responde a lo que acaban de leer.</p>
            </div>
            <div class="intimo-mechanic-item">
                <div class="num">04</div>
                <h5>Conversación</h5>
                <p>Lo que queda entre ustedes dos, no en el plato.</p>
            </div>
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Chefs invitados -->
<section class="intimo-section">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">Chefs invitados</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title" style="max-width:640px;margin:0 auto;">
                Tres cocineros reconocidos de Arequipa, en una sola mesa
            </h2>
        </div>
        <div class="ak-height-60 ak-height-lg-30"></div>
        @php
            $loremChef = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.';
            $chefs = [
                ['img' => 'chef-israel-laura.jpg', 'tag' => 'Chef invitado'],
                ['img' => 'chef-paul-pereda.jpg', 'tag' => 'Chef invitado'],
                ['img' => 'chef-ricardo-serrano.jpg', 'tag' => 'Chef invitado'],
            ];
        @endphp
        <div class="intimo-chefs">
            @foreach ($chefs as $chef)
                <div class="intimo-chef-card">
                    <div class="intimo-chef-photo">
                        <img src="{{ $img($chef['img']) }}" alt="Chef invitado — Íntimo CLAN">
                    </div>
                    <div class="tag">{{ $chef['tag'] }}</div>
                    <p>{{ $loremChef }}</p>
                </div>
            @endforeach
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- El menú -->
<section class="intimo-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">El menú</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title" style="max-width:640px;margin:0 auto;">
                {{ $event->courses }} tiempos, {{ $event->courses }} capítulos
            </h2>
        </div>
        <div class="ak-height-60 ak-height-lg-30"></div>
        @php
            $tiempos = [
                ['n' => '01', 'img' => 'plato-1.jpg', 't' => 'Reino fungí', 'd' => 'Cremoso de hongo shiitake con piña braseada sobre crocante de maíz morado.'],
                ['n' => '02', 'img' => 'plato-2.jpg', 't' => 'El último latido', 'd' => 'Corazón ahumado marinado en aceite con hierbas andinas.'],
                ['n' => '03', 'img' => 'plato-3.jpg', 't' => 'Crustáceos', 'd' => 'Crocante de langostinos con cremoso de tumbo.'],
                ['n' => '04', 'img' => 'plato-4.jpg', 't' => 'Depredación en el litoral', 'd' => 'Cremoso acevichado de hongos y algas.'],
                ['n' => '05', 'img' => 'plato-5.jpg', 't' => 'Barca de papel', 'd' => 'Trucha de chocolate con trucha ahumada.'],
                ['n' => '06', 'img' => 'plato-6.jpg', 't' => 'Dialecto del fuego', 'd' => 'Lomo fino flameado con langostinos y conchas de abanico, sobre puré de camote y tumbo.'],
                ['n' => '07', 'img' => 'plato-7.jpg', 't' => 'Alhaja de roca', 'd' => 'Helado de pulpo en conchas de chocolate blanco.'],
            ];
        @endphp
        <div class="intimo-menu-grid">
            @foreach ($tiempos as $tiempo)
                <div class="intimo-course-card">
                    <div class="photo-wrap">
                        <img src="{{ $img($tiempo['img']) }}" alt="{{ $tiempo['t'] }}">
                        <div class="num-badge">{{ $tiempo['n'] }}</div>
                    </div>
                    <div class="info">
                        <h5>{{ $tiempo['t'] }}</h5>
                        <p>{{ $tiempo['d'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Para quién -->
<section class="intimo-section">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container intimo-narrow">
        <div class="intimo-eyebrow">Para quién es</div>
        <div class="ak-height-25"></div>
        <p style="color:var(--body-color);margin-bottom:30px;">
            Íntimo está diseñado para dos personas, sin importar qué tipo de vínculo compartan.
        </p>
        <div class="intimo-tags">
            <div class="intimo-tag">Pareja</div>
            <div class="intimo-tag">Amigos</div>
            <div class="intimo-tag">Hermanos</div>
            <div class="intimo-tag">Padre e hijo</div>
            <div class="intimo-tag">Madre e hija</div>
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Video -->
<section class="intimo-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">El ritual</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title">Así se vive Íntimo</h2>
        </div>
        <div class="ak-height-50 ak-height-lg-30"></div>
        <div class="intimo-video-wrap">
            <img src="{{ $img('chef-video-poster.jpg') }}" alt="Íntimo">
            @if ($event->video_url)
                <a href="?v={{ $event->video_url }}" class="ak-video-open intimo-video-play">
                    <div class="circle">
                        <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12L0 24V0L20 12Z" fill="#C9A961" />
                        </svg>
                    </div>
                    <span class="label">Ver el video</span>
                </a>
            @else
                <div class="intimo-video-play">
                    <div class="circle">
                        <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 12L0 24V0L20 12Z" fill="#C9A961" opacity=".5" />
                        </svg>
                    </div>
                    <span class="label">Video próximamente</span>
                </div>
            @endif
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Otros momentos -->
<section class="intimo-section">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">La mesa</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title">Otros momentos</h2>
        </div>
        <div class="ak-height-50 ak-height-lg-30"></div>
        <div class="intimo-gallery">
            @foreach (['momento-1.jpg','momento-2.jpg','momento-3.jpg','momento-4.jpg','momento-5.jpg','momento-6.jpg'] as $galimg)
                <a href="{{ $img($galimg) }}" target="_blank" rel="noopener">
                    <img src="{{ $img($galimg) }}" alt="Íntimo">
                </a>
            @endforeach
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Precio -->
<section class="intimo-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">La inversión</div>
        </div>
        <div class="ak-height-40 ak-height-lg-20"></div>
        <div class="intimo-price-card">
            <div class="amount">S/ {{ number_format($event->price, 0) }}</div>
            <div class="per">Por pareja &middot; {{ $event->party_size }} personas</div>
            <ul>
                <li>{{ $event->courses }} tiempos servidos en secuencia</li>
                <li>El libro de Íntimo, parte de la experiencia</li>
                <li>Servicio dedicado, pensado solo para su mesa</li>
                <li>Aforo limitado por turno</li>
            </ul>
            <a href="#reservar" class="intimo-btn solid" style="width:100%;justify-content:center;">Reservar mi mesa</a>
        </div>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Próxima fecha (el evento se está reprogramando: sin countdown ni fecha fija todavía) -->
<section class="intimo-section">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">Próxima fecha</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title">Muy pronto anunciaremos la fecha</h2>
        </div>
        <div class="ak-height-30"></div>
        <p class="intimo-event-meta" style="max-width:560px;margin-left:auto;margin-right:auto;">
            Estamos coordinando la nueva fecha de Íntimo. Déjanos tus datos abajo y te avisamos apenas se abra un cupo.
        </p>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Reservas -->
<section id="reservar" class="intimo-section" style="background:var(--body-bg-color-two);">
    <div class="ak-height-120 ak-height-lg-60"></div>
    <div class="container">
        <div class="ak-section-heading ak-style-1 ak-type-1" style="text-align:center;">
            <div class="intimo-eyebrow">Reserva</div>
            <div class="ak-height-15"></div>
            <h2 class="ak-section-title anim-title">Reserva tu mesa</h2>
        </div>
        <div class="ak-height-50 ak-height-lg-30"></div>

        <form class="intimo-book-form" method="POST" action="{{ route('intimo.reservar') }}">
            @csrf

            @if ($errors->any())
                <div class="intimo-alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="intimo-field">
                <label for="event_schedule_id">Horario</label>
                @if ($schedulesByDate->isEmpty())
                    <p style="color:var(--body-color);">
                        No hay turnos disponibles por ahora. Escríbenos y te avisamos apenas se abra una nueva fecha.
                    </p>
                @else
                    <select name="event_schedule_id" id="event_schedule_id" required>
                        <option value="" disabled {{ old('event_schedule_id') ? '' : 'selected' }}>Selecciona un horario</option>
                        @foreach ($schedulesByDate as $group)
                            <optgroup label="{{ $group['label'] }}">
                                @foreach ($group['schedules'] as $schedule)
                                    <option value="{{ $schedule->id }}" {{ (string) old('event_schedule_id') === (string) $schedule->id ? 'selected' : '' }}>
                                        {{ \Illuminate\Support\Str::of($schedule->start_time)->substr(0, 5) }}
                                        — quedan {{ $schedule->available_spots }} {{ $schedule->available_spots === 1 ? 'mesa' : 'mesas' }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                @endif
            </div>

            <input type="hidden" name="party_size" value="2">

            <div class="intimo-field">
                <label for="relationship_type">¿Con quién vienes?</label>
                <select name="relationship_type" id="relationship_type">
                    <option value="">Prefiero no decirlo</option>
                    <option value="Pareja" {{ old('relationship_type') === 'Pareja' ? 'selected' : '' }}>Pareja</option>
                    <option value="Amigos" {{ old('relationship_type') === 'Amigos' ? 'selected' : '' }}>Amigos</option>
                    <option value="Hermanos" {{ old('relationship_type') === 'Hermanos' ? 'selected' : '' }}>Hermanos</option>
                    <option value="Padre e hijo" {{ old('relationship_type') === 'Padre e hijo' ? 'selected' : '' }}>Padre e hijo</option>
                    <option value="Madre e hija" {{ old('relationship_type') === 'Madre e hija' ? 'selected' : '' }}>Madre e hija</option>
                </select>
            </div>

            <div class="intimo-field-row">
                <div class="intimo-field">
                    <label for="customer_name">Nombre</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required>
                </div>
                <div class="intimo-field">
                    <label for="customer_phone">Teléfono</label>
                    <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" required>
                </div>
            </div>

            <div class="intimo-field">
                <label for="customer_email">Correo</label>
                <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" required>
            </div>

            <div class="intimo-field">
                <label for="notes">¿Algo que debamos saber? (opcional)</label>
                <textarea name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="intimo-btn solid" style="width:100%;justify-content:center;border:none;">
                Continuar al pago — S/ {{ number_format($event->price, 0) }}
            </button>
            <p class="intimo-note" style="text-align:center;">
                No se realiza ningún cobro todavía. En el siguiente paso confirmas el pago de tu reserva.
            </p>
        </form>
    </div>
    <div class="ak-height-120 ak-height-lg-60"></div>
</section>

<!-- Footer propio de Íntimo -->
<footer class="intimo-footer">
    <div class="container intimo-footer-inner">
        <img src="{{ $img('icono-clan.png') }}" alt="CLAN" class="icon">

        <div class="line">
            Reservas al <a href="tel:+51965609626">965 609 626</a>
        </div>

        <div class="line">
            <a href="https://maps.app.goo.gl/fWUcmpiBmsPHM8LAA" target="_blank" rel="noopener">
                Calle Santa Catalina 105 – Arequipa
            </a>
        </div>

        <div class="intimo-social">
            <a href="https://www.facebook.com/Clan.rest.aqp" target="_blank" rel="noopener" aria-label="Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.51 1.49-3.9 3.77-3.9 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.89h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94Z"/></svg>
            </a>
            <a href="https://www.instagram.com/clan_rest_/" target="_blank" rel="noopener" aria-label="Instagram">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07c-1.28.06-2.15.26-2.91.56-.79.31-1.46.72-2.13 1.38C1.25 2.67.84 3.34.53 4.13c-.3.76-.5 1.63-.56 2.91C-.03 8.33-.04 8.74-.04 12s.01 3.67.07 4.95c.06 1.28.26 2.15.56 2.91.31.79.72 1.46 1.38 2.13.66.66 1.34 1.07 2.13 1.38.76.3 1.63.5 2.91.56 1.28.06 1.69.07 4.95.07s3.67-.01 4.95-.07c1.28-.06 2.15-.26 2.91-.56.79-.31 1.46-.72 2.13-1.38.66-.66 1.07-1.34 1.38-2.13.3-.76.5-1.63.56-2.91.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.06-1.28-.26-2.15-.56-2.91-.31-.79-.72-1.46-1.38-2.13C21.33 1.25 20.66.84 19.87.53c-.76-.3-1.63-.5-2.91-.56C15.67-.03 15.26-.04 12-.04Zm0 5.84A6.16 6.16 0 1 0 18.16 12 6.16 6.16 0 0 0 12 5.84Zm0 10.16A4 4 0 1 1 16 12a4 4 0 0 1-4 4Zm6.41-10.4a1.44 1.44 0 1 1-1.44-1.44 1.44 1.44 0 0 1 1.44 1.44Z"/></svg>
            </a>
        </div>

        <div class="intimo-copy">CLAN · Hambre de Crear</div>
    </div>
</footer>

@endsection
