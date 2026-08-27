@extends('layout.layout')

@php
    $title = 'Confirmación';
    $subTitle = 'Reserva confirmada';
    $isFermento = $reservation->event->slug === 'fermento';
@endphp

@section('content')

<style>#preloader { display: none !important; }</style>

@if ($isFermento)
    @php
        $mesaText = $reservation->table
            ? '#' . $reservation->table->table_number . ' · ' . $reservation->party_size . ' personas'
            : $reservation->party_size . ' personas';
        $fechaText = $reservation->schedule->date->format('d/m/Y') . ' · '
            . \Illuminate\Support\Str::of($reservation->schedule->start_time)->substr(0, 5) . ' h';
        $eventRoute = route($reservation->event->slug);
        // Cache-buster por fecha de modificación, mismo criterio que fermento.blade.php.
        $img = function ($name) {
            $path = 'assets/img/fermento/' . $name;
            $full = public_path($path);
            return asset($path) . (file_exists($full) ? '?v=' . filemtime($full) : '');
        };
    @endphp

    <style>
        /* ===== Ocultar cabecera y elementos genéricos del template en esta página ===== */
        header.ak-site_header,
        .ak-commmon-hero,
        footer .ak-footer {
            display: none !important;
        }

        /* ===== Tipografías de marca CLAN (mismas que fermento.blade.php) ===== */
        @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Regular.ttf') }}') format('truetype'); font-weight: 400; font-style: normal; font-display: swap; }
        @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-Medium.ttf') }}') format('truetype'); font-weight: 500; font-style: normal; font-display: swap; }
        @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/clan/poppins/Poppins-SemiBold.ttf') }}') format('truetype'); font-weight: 600; font-style: normal; font-display: swap; }
        @font-face { font-family: 'ClanPoppins'; src: url('{{ asset('assets/fonts/poppins/poppins-300.woff2') }}') format('woff2'); font-weight: 300; font-style: normal; font-display: swap; }

        :root {
            --fermento-yellow: #FBB12F;
            --fermento-bronze: #A7792A;
            --fermento-bg: #1A1410;
            --fermento-white: #F6EEE1;
        }

        .fermento-confirm-screen {
            min-height: 100vh;
            width: 100%;
            background: var(--fermento-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 70px 20px 50px;
            cursor: pointer;
        }

        /* Ring dorado + check + brasas subiendo */
        .fermento-confirm-ring { position: relative; width: 160px; height: 160px; margin: 0 auto 28px; }
        .fermento-confirm-ring svg { width: 100%; height: 100%; }
        .fermento-confirm-ring .ring-progress {
            stroke-dasharray: 440;
            stroke-dashoffset: 440;
            animation: fermentoRingDraw 1s ease-out forwards;
        }
        .fermento-confirm-ring .check-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: fermentoCheckDraw .5s ease-out .9s forwards;
        }
        @keyframes fermentoRingDraw { to { stroke-dashoffset: 0; } }
        @keyframes fermentoCheckDraw { to { stroke-dashoffset: 0; } }
        .fermento-confirm-ring .ember {
            position: absolute; bottom: 14px; left: 50%; width: 6px; height: 6px; border-radius: 50%;
            background: radial-gradient(circle, var(--fermento-yellow) 0%, rgba(251,177,47,0) 70%);
            opacity: 0;
            animation: fermentoEmberRise 2.4s ease-in infinite;
        }
        .ember-1 { left: 34%; animation-delay: .2s; }
        .ember-2 { left: 50%; width: 8px; height: 8px; animation-delay: .7s; }
        .ember-3 { left: 66%; animation-delay: 1.1s; }
        .ember-4 { left: 42%; width: 5px; height: 5px; animation-delay: 1.5s; }
        .ember-5 { left: 58%; animation-delay: 1.9s; }
        .ember-6 { left: 48%; width: 7px; height: 7px; animation-delay: .4s; }
        @keyframes fermentoEmberRise {
            0% { transform: translate(0, 0); opacity: 0; }
            15% { opacity: 1; }
            100% { transform: translate(10px, -140px); opacity: 0; }
        }

        .fermento-confirm-eyebrow {
            font-family: 'ClanPoppins', sans-serif; font-weight: 600;
            letter-spacing: .28em; text-transform: uppercase; font-size: 13px;
            color: var(--fermento-yellow); opacity: .9; margin-bottom: 14px;
        }
        .fermento-confirm-title {
            font-family: 'ClanPoppins', sans-serif; font-weight: 500;
            color: var(--fermento-white); font-size: clamp(26px, 5vw, 38px);
            margin: 0 0 40px;
        }

        .fermento-confirm-card-wrap {
            width: 100%; max-width: 320px; margin: 0 auto;
            border: 1px solid rgba(251,177,47,.25); box-shadow: 0 20px 60px rgba(0,0,0,.5);
        }
        .fermento-confirm-card-wrap canvas { display: block; width: 100%; height: auto; }

        .fermento-confirm-download {
            margin-top: 34px; padding: 15px 32px; border: none; border-radius: 999px;
            background: var(--fermento-yellow); color: #1A1410;
            font-family: 'ClanPoppins', sans-serif; font-weight: 600;
            text-transform: uppercase; letter-spacing: .1em; font-size: 13px; cursor: pointer;
        }
        .fermento-confirm-hint {
            font-family: 'ClanPoppins', sans-serif; color: var(--fermento-white); opacity: .6;
            font-size: 12px; letter-spacing: .04em; margin: 18px 0 0;
        }
    </style>

    <div class="fermento-confirm-screen" id="fermentoConfirmScreen">
        <div class="fermento-confirm-ring">
            <svg viewBox="0 0 160 160">
                <circle cx="80" cy="80" r="70" fill="none" stroke="rgba(251,177,47,0.15)" stroke-width="4" />
                <circle class="ring-progress" cx="80" cy="80" r="70" fill="none" stroke="#FBB12F" stroke-width="4"
                        stroke-linecap="round" transform="rotate(-90 80 80)" />
                <path class="check-path" d="M50 82 L72 104 L112 58" fill="none" stroke="#FBB12F" stroke-width="6"
                      stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="ember ember-1"></span>
            <span class="ember ember-2"></span>
            <span class="ember ember-3"></span>
            <span class="ember ember-4"></span>
            <span class="ember ember-5"></span>
            <span class="ember ember-6"></span>
        </div>

        <div class="fermento-confirm-eyebrow">Reserva confirmada</div>
        <h2 class="fermento-confirm-title">Los esperamos en la mesa</h2>

        <div class="fermento-confirm-card-wrap">
            <canvas id="fermentoStoryCanvas" width="1080" height="1920"></canvas>
        </div>

        <button type="button" id="fermentoDownloadBtn" class="fermento-confirm-download">Descargar mi story</button>
        <p class="fermento-confirm-hint">Toca fuera de la tarjeta para volver a Fermento</p>
    </div>

    <script>
        (function () {
            var canvas = document.getElementById('fermentoStoryCanvas');
            var ctx = canvas.getContext('2d');

            var bg = new Image();
            var logo = new Image();
            var bgLoaded = new Promise(function (resolve) { bg.onload = resolve; });
            var logoLoaded = new Promise(function (resolve) { logo.onload = resolve; });
            bg.src = '{{ $img('story-bg.jpg') }}';
            logo.src = '{{ $img('molto-logo.png') }}';

            // Forzamos la carga de cada peso/tamaño de fuente que se usa en el
            // canvas: document.fonts.ready por sí solo no basta si ningún otro
            // elemento del DOM ya pidió exactamente ese peso antes.
            var fontsNeeded = [
                document.fonts.load('600 30px ClanPoppins'),
                document.fonts.load('300 30px ClanPoppins'),
                document.fonts.load('600 26px ClanPoppins'),
                document.fonts.load('500 46px ClanPoppins'),
                document.fonts.load('400 26px ClanPoppins'),
                document.fonts.load('600 28px ClanPoppins'),
                document.fonts.ready,
                bgLoaded,
                logoLoaded,
            ];

            function drawTracked(text, centerX, y, spacing) {
                var chars = text.split('');
                var widths = chars.map(function (c) { return ctx.measureText(c).width; });
                var total = widths.reduce(function (a, b) { return a + b; }, 0) + spacing * (chars.length - 1);
                var x = centerX - total / 2;
                var prevAlign = ctx.textAlign;
                ctx.textAlign = 'left';
                chars.forEach(function (c, i) {
                    ctx.fillText(c, x, y);
                    x += widths[i] + spacing;
                });
                ctx.textAlign = prevAlign;
            }

            function wrapCentered(text, centerX, y, maxWidth, lineHeight) {
                var words = text.split(' ');
                var lines = [];
                var line = '';
                words.forEach(function (word) {
                    var test = line ? line + ' ' + word : word;
                    if (ctx.measureText(test).width > maxWidth && line) {
                        lines.push(line);
                        line = word;
                    } else {
                        line = test;
                    }
                });
                if (line) lines.push(line);
                lines.forEach(function (l, i) { ctx.fillText(l, centerX, y + i * lineHeight); });
            }

            function row(label, value, labelY, valueY) {
                ctx.fillStyle = '#A7792A';
                ctx.font = '600 26px ClanPoppins';
                drawTracked(label, 540, labelY, 2);

                ctx.fillStyle = '#F6EEE1';
                ctx.font = '500 46px ClanPoppins';
                ctx.textAlign = 'center';
                ctx.fillText(value, 540, valueY);
            }

            function draw() {
                var W = canvas.width, H = canvas.height;
                ctx.clearRect(0, 0, W, H);

                // Fondo: cover, ancla inferior.
                var scale = Math.max(W / bg.naturalWidth, H / bg.naturalHeight);
                var sw = bg.naturalWidth * scale, sh = bg.naturalHeight * scale;
                ctx.drawImage(bg, (W - sw) / 2, H - sh, sw, sh);

                // Marco interior.
                ctx.strokeStyle = 'rgba(251,177,47,0.35)';
                ctx.lineWidth = 2;
                ctx.strokeRect(48, 48, 984, 1824);

                ctx.textAlign = 'center';
                ctx.textBaseline = 'alphabetic';

                // Eyebrow.
                ctx.fillStyle = '#A7792A';
                ctx.font = '600 30px ClanPoppins';
                drawTracked('RESERVA CONFIRMADA', 540, 242, 10);

                // Divisor.
                ctx.strokeStyle = 'rgba(251,177,47,0.35)';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(450, 558);
                ctx.lineTo(630, 558);
                ctx.stroke();

                // Tagline.
                ctx.fillStyle = '#CCBFA8';
                ctx.font = '300 30px ClanPoppins';
                wrapCentered('MOLTO x FORNO — Una noche de masa madre, fuego de leña y vino.', 540, 608, 760, 40);

                // Filas de datos.
                row('A NOMBRE DE', @json($reservation->customer_name), 898, 956);
                row('FECHA', @json($fechaText), 1078, 1136);
                row('MESA', @json($mesaText), 1258, 1316);

                // Logo.
                ctx.drawImage(logo, 390, 1510, 298, 164);

                // Footer.
                ctx.fillStyle = '#7D7361';
                ctx.font = '400 26px ClanPoppins';
                ctx.textAlign = 'center';
                ctx.fillText('Psj. Violín 101 F, San Lázaro — Arequipa', 540, 1738);

                ctx.fillStyle = '#FBB12F';
                ctx.font = '600 28px ClanPoppins';
                ctx.fillText('#Fermento2026', 540, 1798);
            }

            Promise.all(fontsNeeded).then(draw);

            document.getElementById('fermentoDownloadBtn').addEventListener('click', function (e) {
                e.stopPropagation();
                var link = document.createElement('a');
                link.download = 'fermento-reserva-{{ $reservation->code }}.png';
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            document.getElementById('fermentoConfirmScreen').addEventListener('click', function () {
                window.location.href = '{{ $eventRoute }}';
            });
        })();
    </script>
@else
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
@endif

@endsection
