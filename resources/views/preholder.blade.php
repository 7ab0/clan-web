<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Clan" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-libelula.png') }}" />
    <title>Clan — Estamos atizando nuestros fogones</title>
    <link rel="stylesheet" href="{{ asset('assets/css/preholder.css') }}" />
</head>

<body>
    <div class="preholder-page">
        <div class="preholder-bg"></div>
        <div class="preholder-overlay"></div>

        <div class="preholder-content">
            <img class="preholder-icon" src="{{ asset('assets/img/icono-libelula.png') }}" alt="CLAN">
            <p class="preholder-line">Estamos <strong>atizando</strong></p>
            <h1 class="preholder-title">Nuestros Fogones</h1>
        </div>
    </div>

    <audio id="preholderMusic" src="{{ asset('assets/audio/preholder-music.mp3') }}" autoplay muted loop playsinline preload="auto"></audio>
    <button id="preholderAudioToggle" class="preholder-audio-toggle" type="button" aria-label="Activar música" aria-pressed="false">
        <svg class="icon-muted" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
            <path fill="currentColor" d="M16.5 12A4.5 4.5 0 0 0 14 8v2.18l2.45 2.45c.03-.2.05-.42.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.63l1.51 1.51A8.796 8.796 0 0 0 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3 3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06a8.99 8.99 0 0 0 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4 9.91 6.09 12 8.18V4z"/>
        </svg>
        <svg class="icon-unmuted" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
            <path fill="currentColor" d="M3 9v6h4l5 5V4L7 9H3zm13.5 3A4.5 4.5 0 0 0 14 8v8a4.47 4.47 0 0 0 2.5-4zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
        </svg>
    </button>

    <script src="{{ asset('assets/js/preholder.js') }}"></script>
</body>

</html>
