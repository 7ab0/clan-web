<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aniversario ShowClinic — Una noche para celebrar</title>
<meta name="description" content="Celebra con nosotros el aniversario de ShowClinic. Una noche de experiencias, gastronomía y buena compañía en Clan Restaurant, Arequipa.">
<link rel="icon" href="{{ asset('assets/showclinic/img/logosinfondo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/showclinic/css/style.css') }}">
</head>
<body>

@if ($guest)
<!-- ======================= PRE-HOLDER (HISTORIA DE 3 PANTALLAS) ======================= -->
<!-- Sin gate intermedio: con ?inv=CODIGO valido se entra directo aca. -->
<div id="preholder">
  <div class="preholder-frame">
    <div class="preholder-progress" aria-hidden="true">
      <span class="preholder-progress-bar" data-bar="1"></span>
      <span class="preholder-progress-bar" data-bar="2"></span>
      <span class="preholder-progress-bar" data-bar="3"></span>
    </div>

    <div class="preholder-screens">
      <div class="preholder-screen is-active" data-screen="1">
        <img src="{{ asset('assets/showclinic/img/logosinfondo.png') }}" alt="ShowClinic" class="preholder-logo">
        <p class="preholder-wordmark">SHOW CLINIC</p>
        <p class="preholder-teaser-eyebrow">Estamos preparando algo <span class="teaser-strong">grande</span></p>
        <p class="preholder-teaser-text">Y tú serás parte de <span class="teaser-strong">ella.</span></p>
      </div>

      <div class="preholder-screen" data-screen="2">
        @php
          $preholderEyebrow = $guest->compliment ? $guest->profession : 'Tienes una invitación especial';
          $preholderComplimentRaw = trim($guest->compliment ?: 'Hoy, la noche es para ti.');
          if (preg_match('/^(.*\s)(\S+?)([.,;:!?]*)$/u', $preholderComplimentRaw, $complimentParts)) {
              $complimentLead = $complimentParts[1];
              $complimentStrong = $complimentParts[2];
              $complimentTrailing = $complimentParts[3];
          } else {
              $complimentLead = '';
              $complimentStrong = $preholderComplimentRaw;
              $complimentTrailing = '';
          }
        @endphp
        <p class="preholder-compliment-eyebrow">{{ $preholderEyebrow }}</p>
        <h2 class="preholder-guest-name">{{ $guest->name }}</h2>
        <p class="preholder-compliment">{{ $complimentLead }}<span class="compliment-strong">{{ $complimentStrong }}</span>{{ $complimentTrailing }}</p>
      </div>

      <div class="preholder-screen" data-screen="3">
        <div class="preholder-date">
          <span class="preholder-date-num">22</span>
          <span class="preholder-date-month">AGO</span>
        </div>
        <img class="preholder-dragonfly" src="{{ asset('assets/showclinic/img/icono-libelula-clan.png') }}" alt="CLAN">
      </div>
    </div>

    <div class="preholder-tap" aria-hidden="true">
      <div class="preholder-tap-prev" id="preholderPrev"></div>
      <div class="preholder-tap-next" id="preholderNext"></div>
    </div>

    <p class="preholder-hint">Toca para continuar</p>
  </div>
</div>
@endif

<!-- ======================= CONTENIDO PRINCIPAL ======================= -->
<div id="main" hidden>

  <!-- NAV -->
  <nav class="nav">
    <img src="{{ asset('assets/showclinic/img/logosinfondo.png') }}" alt="ShowClinic" class="nav-logo">
    <div class="nav-links">
      @if ($guest)
        <a href="#confirmacion">Confirmar</a>
      @endif
      <a href="#itinerario">Itinerario</a>
      <a href="#experiencia">Experiencia</a>
      <a href="#sorteo">Sorteo</a>
      <a href="#ubicacion">Ubicación</a>
    </div>
  </nav>

  <!-- HERO -->
  <header class="hero">
    <div class="hero-bg" style="background-image:url('{{ asset('assets/showclinic/img/hero-recepcion.jpg') }}')"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content reveal">
      <p class="eyebrow">Celebramos juntos</p>
      <h1 class="hero-title">Aniversario<br><em>ShowClinic</em></h1>
      <p class="hero-tagline">Seguridad que se siente, belleza que se nota.</p>

      <div class="hero-meta">
        <div class="meta-item">
          <span class="meta-label">Fecha</span>
          <span class="meta-value" id="eventDate">22 de agosto, 2026</span>
        </div>
        <div class="meta-divider"></div>
        <div class="meta-item">
          <span class="meta-label">Hora</span>
          <span class="meta-value">7:00 p.m.</span>
        </div>
        <div class="meta-divider"></div>
        <div class="meta-item">
          <span class="meta-label">Lugar</span>
          <span class="meta-value">Clan Restaurant</span>
        </div>
      </div>

      <div class="countdown" id="countdown" aria-live="polite">
        <div class="cd-box"><span class="cd-num" id="cdDays">00</span><span class="cd-label">días</span></div>
        <div class="cd-box"><span class="cd-num" id="cdHours">00</span><span class="cd-label">horas</span></div>
        <div class="cd-box"><span class="cd-num" id="cdMin">00</span><span class="cd-label">min</span></div>
        <div class="cd-box"><span class="cd-num" id="cdSec">00</span><span class="cd-label">seg</span></div>
      </div>
    </div>
    <div class="scroll-cue">Desliza para ver el programa</div>
  </header>

  @if ($guest)
  <!-- CONFIRMACIÓN DE ASISTENCIA -->
  <section id="confirmacion" class="section confirmacion">
    <div class="section-head reveal">
      <p class="eyebrow">Tu respuesta</p>
      <h2>Confirma tu asistencia</h2>
    </div>

    <div class="confirm-box reveal">

      <div id="confirmSummary" class="confirm-summary" @if ($guest->status === 'pendiente') hidden @endif>
        @if ($guest->status === 'confirmado')
          <p class="confirm-icon">✓</p>
          <h3>¡Gracias, {{ $guest->name }}!</h3>
          <p class="confirm-text">Ya confirmaste tu asistencia. Te esperamos el 22 de agosto en Clan Restaurant.</p>
          @if ($guest->plus_one)
            <p class="confirm-detail">Acompañante: {{ $guest->companion_name ?: 'sí' }}</p>
          @endif
          @if ($guest->preferences)
            <p class="confirm-detail">Preferencias: {{ $guest->preferences }}</p>
          @endif
        @elseif ($guest->status === 'rechazado')
          <p class="confirm-icon">·</p>
          <h3>Gracias por avisarnos, {{ $guest->name }}</h3>
          <p class="confirm-text">Registramos que no podrás acompañarnos esta vez. ¡Esperamos verte en la próxima!</p>
        @endif
        <button type="button" id="updateResponseBtn" class="btn-outline">Actualizar mi respuesta</button>
      </div>

      <div id="confirmForm" @if ($guest->status !== 'pendiente') hidden @endif>
        <p class="confirm-question">¿Confirmas tu asistencia, {{ $guest->name }}?</p>

        <div class="confirm-choice">
          <button type="button" id="choiceYes" class="btn-enter"><span>Sí, confirmo</span></button>
          <button type="button" id="choiceNo" class="btn-outline">No podré asistir</button>
        </div>

        <form method="POST" action="{{ route('showclinic.confirmar') }}" id="confirmDetails" class="confirm-details" hidden>
          @csrf
          <input type="hidden" name="code" value="{{ $guest->code }}">
          <input type="hidden" name="response" value="confirmado">

          <label class="toggle-row" for="plusOneToggle">
            <input type="checkbox" name="plus_one" value="1" id="plusOneToggle" @checked($guest->plus_one)>
            <span>¿Vienes acompañado/a?</span>
          </label>

          <div id="companionField" class="field" @if (! $guest->plus_one) hidden @endif>
            <label for="companion_name">Nombre de tu acompañante</label>
            <input type="text" id="companion_name" name="companion_name" value="{{ old('companion_name', $guest->companion_name) }}" placeholder="Nombre completo">
          </div>

          <div class="field">
            <label for="preferences">Preferencias o restricciones alimentarias</label>
            <textarea id="preferences" name="preferences" rows="3" placeholder="Ej. vegetariano, alergias, etc. (opcional)">{{ old('preferences', $guest->preferences) }}</textarea>
          </div>

          <button type="submit" class="btn-enter"><span>Enviar confirmación</span></button>
        </form>

        <form method="POST" action="{{ route('showclinic.confirmar') }}" id="declineForm" hidden>
          @csrf
          <input type="hidden" name="code" value="{{ $guest->code }}">
          <input type="hidden" name="response" value="rechazado">
        </form>
      </div>

    </div>
  </section>
  @endif

  <!-- ITINERARIO -->
  <section id="itinerario" class="section">
    <div class="section-head reveal">
      <p class="eyebrow">Programa de la noche</p>
      <h2>Así viviremos la velada</h2>
    </div>

    <div class="timeline">
      <div class="tl-item reveal">
        <div class="tl-time">7:30 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Bienvenida</h3>
          <p>Recepción de invitados, welcome drink, bocaditos de bienvenida, photocall y networking.</p>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">8:00 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Show Cooking Experience</h3>
          <p>Una experiencia gastronómica en vivo junto a nuestro chef invitado, acompañada de cócteles y bocaditos.</p>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">8:30 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Bienvenida oficial</h3>
          <p>Palabras del Dr. Erick Espetia y brindis de aniversario.</p>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">8:40 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Música en vivo</h3>
          <p>Una velada con música en vivo, cócteles y bocaditos. Activa tu participación en el Gran Sorteo de Aniversario.</p>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">9:30 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>DJ Session</h3>
          <p>El ambiente cambia para seguir celebrando una noche especial junto a nuestros invitados.</p>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">10:00 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Sorteo &amp; Gift Bags</h3>
          <p>Anuncio de los ganadores y entrega de premios. Cada invitado recibirá un Gift Bag exclusivo de ShowClinic.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- EXPERIENCIA GASTRONÓMICA -->
  <section id="experiencia" class="section section-dark">
    <div class="particles" aria-hidden="true"></div>
    <div class="section-head reveal">
      <p class="eyebrow">En Clan Restaurant</p>
      <h2>Una experiencia por estaciones</h2>
      <p class="section-lead">
        La noche se sirve, se recorre y se comparte. En vez de un menú tradicional,
        Clan nos recibe con distintas estaciones repartidas por el espacio, cada una
        con su propia propuesta de platos y bocaditos — para que la experiencia
        gastronómica sea también un momento de descubrimiento.
      </p>
    </div>

    <div class="gallery reveal">
      <img src="{{ asset('assets/showclinic/img/tratamiento-1.jpg') }}" alt="ShowClinic" class="gallery-img tall">
      <img src="{{ asset('assets/showclinic/img/editorial-1.jpg') }}" alt="ShowClinic" class="gallery-img">
      <img src="{{ asset('assets/showclinic/img/editorial-4.jpg') }}" alt="ShowClinic" class="gallery-img wide">
      <img src="{{ asset('assets/showclinic/img/editorial-2.jpg') }}" alt="ShowClinic" class="gallery-img">
    </div>

    <div class="host-card reveal">
      <img src="{{ asset('assets/showclinic/img/dr-erick.jpg') }}" alt="Dr. Erick Espetia">
      <div>
        <p class="eyebrow">Tu anfitrión</p>
        <h3>Dr. Erick Espetia</h3>
        <p>Nos dará la bienvenida oficial de la noche y brindará con nosotros por
          este nuevo año de ShowClinic.</p>
      </div>
    </div>
  </section>

  <!-- SORTEO -->
  <section id="sorteo" class="section">
    <div class="section-head reveal">
      <p class="eyebrow">Gran Sorteo de Aniversario</p>
      <h2>Participa por estos premios</h2>
    </div>
    <div class="prizes reveal">
      <div class="prize-card">
        <span class="prize-num">01</span>
        <h3>Botox</h3>
      </div>
      <div class="prize-card">
        <span class="prize-num">02</span>
        <h3>Aumento de labios</h3>
        <p>con ácido hialurónico</p>
      </div>
      <div class="prize-card">
        <span class="prize-num">03</span>
        <h3>Gift Card 50%</h3>
        <p>de descuento en cualquier tratamiento</p>
      </div>
      <div class="prize-card">
        <span class="prize-num">04</span>
        <h3>Limpieza facial</h3>
        <p>profesional</p>
      </div>
    </div>
  </section>

  <!-- DRESS CODE + UBICACIÓN -->
  <section id="ubicacion" class="section section-dark split">
    <div class="particles" aria-hidden="true"></div>
    <div class="split-col reveal">
      <p class="eyebrow">Dress code</p>
      <h2>Elegante</h2>
      <p class="section-lead">Ven a celebrar con nosotros vestido/a para la ocasión — una noche elegante merece una entrada elegante.</p>
    </div>
    <div class="split-col reveal">
      <p class="eyebrow">Ubicación</p>
      <h2>Clan Restaurant</h2>
      <p class="section-lead">Calle Santa Catalina 105, Arequipa</p>
      <a class="btn-outline" href="https://www.google.com/maps/search/?api=1&query=Calle+Santa+Catalina+105+Arequipa+Clan+Restaurant" target="_blank" rel="noopener">Ver en Google Maps →</a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="particles" aria-hidden="true"></div>
    <img src="{{ asset('assets/showclinic/img/logosinfondo.png') }}" alt="ShowClinic" class="footer-logo">
    <p class="footer-tagline">Seguridad que se siente, belleza que se nota.</p>
    <div class="footer-links">
      <a href="https://www.instagram.com/showclinic/" target="_blank" rel="noopener">Instagram</a>
      <a href="https://www.facebook.com/profile.php?id=100063552821608" target="_blank" rel="noopener">Facebook</a>
      <a href="https://showclinic.com" target="_blank" rel="noopener">showclinic.com</a>
    </div>
    <p class="footer-copy">© 2026 ShowClinic — Yanahuara, Arequipa</p>
  </footer>

</div>

<!-- ======================= AUDIO DE FONDO ======================= -->
<!-- Fuera de #main a propósito: sin gate, debe quedar visible desde el
     primer render, durante las 3 pantallas del pre-holder y despues. -->
<audio id="bgMusic" src="{{ asset('assets/showclinic/audio/event-music.mp3') }}" loop muted playsinline preload="auto"></audio>
<button id="audioToggle" class="audio-toggle" type="button" aria-label="Activar música" aria-pressed="false">
  <svg class="icon-muted" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
    <path fill="currentColor" d="M16.5 12A4.5 4.5 0 0 0 14 8v2.18l2.45 2.45c.03-.2.05-.42.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.63l1.51 1.51A8.796 8.796 0 0 0 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3 3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06a8.99 8.99 0 0 0 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4 9.91 6.09 12 8.18V4z"/>
  </svg>
  <svg class="icon-unmuted" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
    <path fill="currentColor" d="M3 9v6h4l5 5V4L7 9H3zm13.5 3A4.5 4.5 0 0 0 14 8v8a4.47 4.47 0 0 0 2.5-4zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
  </svg>
</button>

<script src="{{ asset('assets/showclinic/js/main.js') }}"></script>
</body>
</html>
