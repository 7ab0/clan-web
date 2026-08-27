<!DOCTYPE html>
<html class="no-js" lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Clan" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-libelula.png') }}" />
    <title>Clan — Algo se esta cocinando</title>
    <link rel="stylesheet" href="{{ asset('assets/css/maintenance.css') }}" />
</head>

<body>
    <div class="maintenance-page">
        <div class="maintenance-card">
            <img class="maintenance-logo" src="{{ asset('assets/img/clan-logo.svg') }}" alt="CLAN Cocina de Autor">

            <p class="maintenance-eyebrow">Clan &mdash; cocina de autor</p>
            <h1 class="maintenance-title">Algo se está cocinando</h1>
            <p class="maintenance-lead">Estamos afinando el fuego. Vuelve pronto &mdash; o entra ya, si conoces la palabra.</p>

            <form class="maintenance-form" method="POST" action="{{ route('maintenance.unlock') }}">
                @csrf
                <input
                    class="maintenance-input"
                    type="text"
                    name="word"
                    placeholder="susurra la palabra"
                    autocomplete="off"
                    autocapitalize="off"
                    autofocus
                >
                <button class="maintenance-submit" type="submit">Entrar</button>
                @if (session('error'))
                    <p class="maintenance-error">{{ session('error') }}</p>
                @endif
            </form>
        </div>
    </div>
</body>

</html>
