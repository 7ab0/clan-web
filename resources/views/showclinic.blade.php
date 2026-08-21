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
  @php
    // Nombre y apellido en líneas separadas (se usa en el saludo del Hero).
    // Antes se calculaba dentro del pre-holder (ya eliminado) — se mantiene
    // aquí para no romper el saludo personalizado.
    $guestNameParts = preg_split('/\s+/', trim($guest->name), 2);
    $guestFirstName = $guestNameParts[0] ?? '';
    $guestLastName = $guestNameParts[1] ?? '';
  @endphp
@endif

<!-- ======================= CONTENIDO PRINCIPAL ======================= -->
<!-- Pre-holder (historia de 3 pantallas) eliminado a pedido del cliente:
     se entra directo al sitio principal, sin pantalla intermedia. -->
<div id="main">

  <!-- NAV -->
  <nav class="nav">
    <img src="{{ asset('assets/showclinic/img/logosinfondo.png') }}" alt="ShowClinic" class="nav-logo">
    <div class="nav-links">
      @if ($guest)
        <a href="#confirmacion">Confirmar</a>
      @endif
      <a href="#itinerario">Itinerario</a>
      <a href="#experiencia">Experiencia</a>
      <a href="#momento-sorteo-gift-bags">Sorteo</a>
      <a href="#ubicacion">Ubicación</a>
    </div>
  </nav>

  <!-- HERO -->
  <header class="hero">
    <div class="hero-bg" style="background-image:url('{{ asset('assets/showclinic/img/hero-recepcion.jpg') }}')"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content reveal">
      <p class="hero-greeting">
        <strong>@if ($guest){{ $guestFirstName }}@else Bienvenido/a @endif</strong> Celebramos juntos
        <span class="hero-greeting-sub">Nuestro 4<span class="ord">to</span> Aniversario</span>
      </p>

      <img src="{{ asset('assets/showclinic/img/logosinfondo.png') }}" alt="ShowClinic" class="hero-logo">
      <p class="hero-wordmark">SHOWCLINIC</p>

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
  <!-- BOTÓN FLOTANTE DE CONFIRMACIÓN (siempre accesible, lleva a la sección grande al final) -->
  <a href="#confirmacion" id="rsvpFab" class="rsvp-fab rsvp-fab-{{ $guest->status }}">
    @if ($guest->status === 'confirmado')
      <span class="rsvp-fab-icon">✓</span><span>Asistencia confirmada</span>
    @elseif ($guest->status === 'rechazado')
      <span class="rsvp-fab-icon">·</span><span>Actualizar respuesta</span>
    @else
      <span class="rsvp-fab-icon">✓</span><span>Confirmar asistencia</span>
    @endif
  </a>
  @endif

  <!-- ITINERARIO (VISTA GENERAL) -->
  <section id="itinerario" class="section">
    <div class="section-head reveal">
      <p class="eyebrow">Disfruta de esta experiencia con nosotros</p>
      <h2>Así viviremos el aniversario</h2>
      <p class="section-lead">
        Comparte con nosotros el programa completo — desliza para ver más
        detalles de cada momento.
      </p>
    </div>

    <div class="timeline">
      <div class="tl-item reveal">
        <div class="tl-time">7:30 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Bienvenida</h3>
          <p>Recepción, welcome drink y photocall para abrir la noche.</p>
          <a class="tl-more" href="#momento-bienvenida">Ver detalle ↓</a>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">8:00 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Show Cooking Experience</h3>
          <p>Una experiencia gastronómica exclusiva junto a nuestro chef invitado desde Lima, Paolo Zambrano.</p>
          <a class="tl-more" href="#momento-show-cooking">Ver detalle ↓</a>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">8:45 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Bienvenida Oficial</h3>
          <p>Palabras del Dr. Erick Espetia y brindis de aniversario.</p>
          <a class="tl-more" href="#momento-bienvenida-oficial">Ver detalle ↓</a>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">9:00 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Show Musical</h3>
          <p>Presentación musical a cargo de Javier Lazo, compositor y productor musical dedicado a reinterpretar la música afroperuana.</p>
          <a class="tl-more" href="#momento-musica-en-vivo">Ver detalle ↓</a>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">9:30 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>DJ Session</h3>
          <p>La pista se enciende para seguir celebrando.</p>
          <a class="tl-more" href="#momento-dj-session">Ver detalle ↓</a>
        </div>
      </div>
      <div class="tl-item reveal">
        <div class="tl-time">10:00 p.m.</div>
        <div class="tl-line"><span class="tl-dot"></span></div>
        <div class="tl-body">
          <h3>Sorteo &amp; Gift Bags</h3>
          <p>Anuncio de ganadores y Gift Bag exclusivo para cada invitado.</p>
          <a class="tl-more" href="#momento-sorteo-gift-bags">Ver detalle ↓</a>
        </div>
      </div>
    </div>
  </section>

  <!-- MOMENTO 1 — BIENVENIDA -->
  <section id="momento-bienvenida" class="section-dark itinerary-detail">
    <div class="particles" aria-hidden="true"></div>

    <div class="detail-hero-banner" style="background-image:url('{{ asset('assets/showclinic/img/momento1-cava.jpg') }}')"></div>

    <div class="detail-hero-body">
      <div class="section-head reveal">
        <p class="eyebrow">Momento 1 de 6 · 7:30 p.m.</p>
        <h2>Bienvenida</h2>
      </div>

      <p class="section-lead reveal">
        Las puertas de Clan se abren para dar inicio a la velada. Cada invitado
        es recibido con calidez, copa en mano, mientras el espacio se llena de
        música suave y los primeros bocaditos empiezan a circular entre los
        asistentes.
      </p>

      <ul class="tl-list detail-list detail-list-2col reveal">
        <li>Recepción de invitados</li>
        <li>Welcome Drink de bienvenida</li>
        <li>Bocaditos de bienvenida</li>
        <li>Photocall &amp; Networking</li>
      </ul>
    </div>
  </section>

  <!-- MOMENTO 2 — SHOW COOKING EXPERIENCE -->
  <section id="momento-show-cooking" class="itinerary-detail">
    <div class="detail-split reveal">
      <div class="detail-split-media detail-split-media-framed">
        <img src="{{ asset('assets/showclinic/img/paolo-zambrano.jpg') }}" alt="Chef Paolo Zambrano">
      </div>
      <div class="detail-split-text">
        <div class="section-head section-head-center">
          <p class="eyebrow">Momento 2 de 6 · 8:00 p.m.</p>
          <h2>Show Cooking Experience</h2>
        </div>
        <p class="section-lead">
          Una experiencia gastronómica exclusiva junto a nuestro chef
          invitado desde Lima, Paolo Zambrano, quien toma el centro del
          escenario para un show cooking en vivo: técnica, fuego y sabor a
          la vista de todos, mientras los cócteles de autor y los
          bocaditos de la casa siguen acompañando la noche.
        </p>
        <ul class="tl-list detail-list">
          <li>Show cooking en vivo con el chef Paolo Zambrano</li>
          <li>Cócteles de autor</li>
          <li>Bocaditos de la casa</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- MOMENTO 3 — BIENVENIDA OFICIAL -->
  <section id="momento-bienvenida-oficial" class="section-dark itinerary-detail">
    <div class="particles" aria-hidden="true"></div>
    <div class="detail-split reverse reveal">
      <div class="detail-split-media">
        <img src="{{ asset('assets/showclinic/img/dr-erick.jpg') }}" alt="Dr. Erick Espetia">
      </div>
      <div class="detail-split-text">
        <div class="section-head">
          <p class="eyebrow">Momento 3 de 6 · 8:45 p.m.</p>
          <h2>Bienvenida Oficial</h2>
        </div>
        <p class="section-lead">
          Con todos los invitados ya instalados, el Dr. Erick Espetia toma la
          palabra para dar la bienvenida oficial a esta nueva edición del
          aniversario de ShowClinic — un brindis compartido para celebrar un
          año más de historia.
        </p>
        <ul class="tl-list detail-list">
          <li>Palabras del Dr. Erick Espetia</li>
          <li>Brindis de aniversario</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- MOMENTO 4 — SHOW MUSICAL -->
  <section id="momento-musica-en-vivo" class="section itinerary-detail">
    <div class="section-head reveal">
      <p class="eyebrow">Momento 4 de 6 · 9:00 p.m.</p>
      <h2>Show Musical</h2>
      <p class="section-lead">
        Presentación musical a cargo de Javier Lazo, compositor y productor
        musical dedicado a reinterpretar la música afroperuana, mientras
        cócteles y bocaditos siguen circulando entre los invitados. Es
        también el momento en que se activa la participación de todos en el
        Gran Sorteo de Aniversario.
      </p>
    </div>

    <ul class="tl-list detail-list reveal">
      <li>Show musical con Javier Lazo</li>
      <li>Continúan cócteles y bocaditos</li>
      <li>Activación del Gran Sorteo de Aniversario</li>
    </ul>

    <div class="photo-gallery reveal">
      <img src="{{ asset('assets/showclinic/img/javier-lazo.jpg') }}" alt="Javier Lazo" class="photo-gallery-img">
      <a class="photo-slot" href="https://www.youtube.com/watch?v=EsBH1UY__3s" target="_blank" rel="noopener" aria-label="Ver video en YouTube">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M10 8.5l6 3.5-6 3.5v-7Z" fill="currentColor" stroke="none"/><rect x="3" y="6" width="18" height="12" rx="2.5"/></svg>
        <span>Ver video en YouTube</span>
      </a>
    </div>
  </section>

  <!-- MOMENTO 5 — DJ SESSION -->
  <section id="momento-dj-session" class="section section-dark itinerary-detail">
    <div class="particles" aria-hidden="true"></div>
    <div class="section-head reveal">
      <p class="eyebrow">Momento 5 de 6 · 9:30 p.m.</p>
      <h2>DJ Session</h2>
      <p class="section-lead">
        El ambiente cambia por completo: las luces bajan, el volumen sube y la
        pista se abre para que los invitados sigan celebrando una noche
        especial hasta el momento del gran sorteo.
      </p>
    </div>
  </section>

  <!-- MOMENTO 6 — SORTEO & GIFT BAGS -->
  <section id="momento-sorteo-gift-bags" class="section itinerary-detail">
    <div class="section-head reveal">
      <p class="eyebrow">Momento 6 de 6 · 10:00 p.m.</p>
      <h2>Sorteo &amp; Gift Bags</h2>
      <p class="section-lead">
        El cierre de la noche llega con el anuncio de los ganadores del Gran
        Sorteo de Aniversario. Como agradecimiento por acompañarnos, cada
        invitado se lleva a casa un Gift Bag exclusivo de ShowClinic.
      </p>
    </div>

    <p class="eyebrow gift-bag-caption reveal">Gift Bag exclusivo para cada invitado</p>

    <h3 class="prizes-title reveal">Participa por estos premios</h3>

    <div class="prizes reveal">
      <div class="prize-card">
        <h3>Botox</h3>
      </div>
      <div class="prize-card">
        <h3>Aumento de labios</h3>
        <p>con ácido hialurónico</p>
      </div>
      <div class="prize-card">
        <h3>Gift Card 50%</h3>
        <p>de descuento en cualquier tratamiento</p>
      </div>
      <div class="prize-card">
        <h3>Armonización Full Face</h3>
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

    <div class="photo-carousel reveal" aria-label="Galería de platos de Clan Restaurant">
      <button type="button" class="carousel-arrow carousel-prev" aria-label="Foto anterior">&#8592;</button>
      <div class="carousel-track">
        <img src="{{ asset('assets/showclinic/img/platos/plato-01.jpg') }}" alt="Plato de autor — Clan Restaurant" class="carousel-slide">
        <img src="{{ asset('assets/showclinic/img/platos/plato-02.jpg') }}" alt="Plato de autor — Clan Restaurant" class="carousel-slide">
        <img src="{{ asset('assets/showclinic/img/platos/plato-03.jpg') }}" alt="Plato de autor — Clan Restaurant" class="carousel-slide">
        <img src="{{ asset('assets/showclinic/img/platos/plato-04.jpg') }}" alt="Plato de autor — Clan Restaurant" class="carousel-slide">
        <img src="{{ asset('assets/showclinic/img/platos/plato-05.jpg') }}" alt="Plato de autor — Clan Restaurant" class="carousel-slide">
        <img src="{{ asset('assets/showclinic/img/platos/plato-06.jpg') }}" alt="Plato de autor — Clan Restaurant" class="carousel-slide">
      </div>
      <button type="button" class="carousel-arrow carousel-next" aria-label="Foto siguiente">&#8594;</button>
    </div>

  </section>

  <!-- DRESS CODE + UBICACIÓN -->
  <section id="ubicacion" class="section section-dark split">
    <div class="particles" aria-hidden="true"></div>
    <div class="split-col reveal">
      <p class="eyebrow">Dress code</p>
      <h2>Cóctel</h2>
      <p class="section-lead">Ven a celebrar con nosotros con vestimenta de cóctel — una noche elegante merece una entrada elegante.</p>
    </div>
    <div class="split-col reveal">
      <p class="eyebrow">Ubicación</p>
      <h2>Clan Restaurant</h2>
      <p class="section-lead">Calle Santa Catalina 105, Arequipa</p>
      <a class="btn-outline" href="https://www.google.com/maps/search/?api=1&query=Calle+Santa+Catalina+105+Arequipa+Clan+Restaurant" target="_blank" rel="noopener">Ver en Google Maps →</a>
    </div>
  </section>

  @if ($guest)
  <!-- CONFIRMACIÓN DE ASISTENCIA (al final de la invitación) -->
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
     primer render. -->
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
