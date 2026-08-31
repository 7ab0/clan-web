# CLAN Web — Contexto del proyecto

## Qué es esto
Hub principal de CLAN (restaurante de autor, Arequipa) construido en
Laravel. Además del sitio de CLAN, este mismo proyecto aloja de forma
integrada:
- Show Clinic: landing de evento para un cliente externo (clínica
  estética), completamente standalone visualmente (no usa el layout
  de CLAN — vista propia `showclinic.blade.php`, CSS y fuentes
  propias en `public/assets/showclinic/`)
- Íntimo: sistema de reservas/eventos de otra experiencia CLAN.
  Técnicamente extiende `layout.layout` (el layout base de CLAN) pero
  oculta header/footer/hero genéricos por CSS inline y aplica
  tipografías de marca propias, logrando una identidad visual
  distinta sin ser un layout completamente separado — no lo trates
  como standalone puro al tocar el layout compartido
- Fermento: colaboración CLAN × FORNO/MOLTO — landing con reserva de
  mesa por fecha, seña coordinada por WhatsApp (sin cobro en línea
  todavía) y confirmación con tarjeta tipo story descargable. Mismo
  patrón que Íntimo: extiende `layout.layout`, oculta header/footer
  genéricos por CSS inline, tipografías propias (Canela + Inter,
  `public/assets/fonts/canela/` e `.../inter/`) — tampoco es
  standalone puro. Comparte `ReservationController`/
  `PaymentController` y las rutas `/reservas/{code}/pago` y
  `/reservas/{code}/confirmacion` con Íntimo

## Stack
- Laravel 11, PHP ^8.2
- Local: SQLite (Herd, clan-web.test) — `DB_CONNECTION=sqlite`, archivo
  en `database/database.sqlite`
- Producción: MySQL en Laravel Cloud (clan-web-db)
- Dominio producción: clan-rest.club
- Repo: github.com/7ab0/clan-web

## Rutas principales
- / — home CLAN
- /menu — carta
- /experiencias — hub que enlaza a Show Clinic e Íntimo
- /intimo/{token?} — landing/reserva de Íntimo (mismo layout base de
  CLAN, con header/footer ocultos e identidad propia por CSS)
- /intimo/reservar, /reservas/{code}/pago, /reservas/{code}/confirmacion
  — flujo de reserva y pago de Íntimo (mismas rutas de pago/confirmación
  compartidas con Fermento, ver abajo)
- /fermento/{token?} — landing/reserva de Fermento (mismo layout base
  de CLAN, header/footer ocultos, identidad Canela/Inter); el token
  opcional dispara el saludo personalizado "Hola, {nombre}" en el
  preloader cuando es un link de `fermento_guests` O de `influencers`
  (ver `EventController::fermento()` — primero busca en FermentoGuest,
  si no encuentra prueba con Influencer; ambos modelos exponen
  `->first_name`/`->opened_at` igual). Los links de influencers no
  tienen landing propia: abren este mismo preloader + la landing
  completa de siempre
- /fermento/reservar — crea la reserva (mismo flujo que Íntimo, ver
  `ReservationController`)
- /reservas/admin, /reservas/admin/clientes, /reservas/admin/invitados
  — panel admin de reservas de Fermento + Íntimo: confirma pago
  manualmente, edita/elimina reservas, agrega reservas e invitados a
  mano (botones "Agregar reserva"/"Agregar invitado", ver
  `ReservationAdminController::storeReservation()`/`storeGuest()`), ve
  la base de clientes, y hace outreach por WhatsApp a los invitados de
  Fermento. Login propio, middleware `reservas.admin` (contraseña
  simple, mismo patrón que showclinic/admin)
- /reservas/revision — panel SEPARADO de solo lectura (sin editar,
  eliminar ni confirmar pagos) que lista únicamente reservas ya
  confirmadas (nunca `is_test`), pensado para compartir con FORNO/MOLTO
  sin darles el acceso completo de `/reservas/admin`. Login y sesión
  propios (`reservas_review_authenticated`), middleware
  `reservas.review` → `ReservasReviewAuth`, contraseña en
  `RESERVAS_REVIEW_PASSWORD`, sin default — si la env var no está
  seteada el login falla siempre (mismo criterio que
  `RESERVAS_ADMIN_PASSWORD`; ver `config/services.php`, clave
  `reservas.review_password`)
- /mantenimiento — vista del muro de mantenimiento general (ver
  `MaintenanceMode` abajo), con su propio formulario de acceso
- /showclinic — landing standalone del evento (pre-holder personalizado
  vía ?inv=CODIGO, countdown, RSVP, música de fondo)
- /showclinic/admin — panel de gestión de invitados (login propio,
  protegido por middleware `showclinic.admin` / `SHOWCLINIC_ADMIN_PASSWORD`)
- /influencers/admin — panel de staff del pre-cóctel de influencers de
  Fermento (martes 1/9/2026, 7:00 p.m., Psj. Violín 101 F, San Lázaro,
  Plaza Campo Redondo — Arequipa). No hay landing pública propia — el
  link personalizado de cada influencer es `/fermento/{token}`, la
  landing normal de Fermento (ver arriba). La confirmación de
  asistencia se coordina por WhatsApp fuera del sistema; el staff la
  refleja a mano acá, en el campo `status`. A diferencia de
  `/reservas/revision` (solo lectura), este panel SÍ permite dar de
  alta, editar y hacer check-in manual el día del evento, además de
  cargar posts/stories/reels con métricas. Login y sesión propios,
  middleware `influencers.admin` → `InfluencersAdminAuth`, contraseña en
  `INFLUENCERS_ADMIN_PASSWORD` (sin default, mismo criterio que
  `RESERVAS_ADMIN_PASSWORD`; ver `config/services.php`, clave
  `influencers.admin_password`). Tablas propias `influencers` e
  `influencer_posts`, sin relación con `fermento_guests` ni con las
  reservas de mesa. Los 18 influencers reales del pre-cóctel (31/8/2026)
  se cargan con `php artisan influencers:seed` (idempotente por
  teléfono, ver `SeedInfluencers`)
- /influencers/admin/invitacion — genera y descarga/comparte la imagen
  de invitación personalizada por influencer (Canvas 1080×1920 dibujado
  100% en el navegador, mismo mecanismo que la story card de
  `home/confirmacion.blade.php`, sin backend nuevo). Selector de
  influencer + botón "Generar"; botón "Descargar" siempre visible,
  botón "Compartir" (`navigator.share` con archivos) solo si el
  navegador lo soporta. Fondo en `public/assets/img/
  influencers-precoctel-bg.jpg` (bajado de Figma, fileKey
  `JcHyaPxXN9LsUDbJ2M1WTT` nodo `2:2`), tipografías Canela Medium +
  Inter (normal e itálica) ya self-hosteadas del resto del proyecto

## Convenciones importantes
- Show Clinic NO hereda el layout/nav de CLAN — tiene su propia
  identidad visual completa. Íntimo SÍ extiende `layout.layout` pero
  oculta los elementos genéricos del template vía CSS — ten cuidado al
  modificar `layout.layout`, afecta tanto al sitio CLAN como a Íntimo
- La tabla `guests` es de Show Clinic (code, name, profession,
  compliment, status RSVP: pendiente/confirmado/rechazado, plus_one,
  companion_name). La tabla `intimo_guests` es de Íntimo (event_id,
  token único de 12 chars, opened_at, whatsapp_sent_at). La tabla
  `fermento_guests` es de Fermento (token único, outreach por WhatsApp
  en etapas: `invite_sent_at`, `interest_confirmed_at`, etc.) — las
  tres tienen nombres similares pero esquemas distintos, NO confundir
  ni fusionar
- La tabla `customers` es la base de clientes simple, alimentada
  automáticamente por cada reserva (dedup por teléfono) — por decisión
  del usuario, scopeada solo a Fermento por ahora, no a Íntimo
- La tabla `influencers` es del pre-cóctel de Fermento para influencers
  (token propio de 8 chars, opened_at, status invitado/confirmado/
  declinado/asistio, followers_count, confirmed_at/attended_at) — sin
  relación con `fermento_guests` (invitados normales de la reserva de
  mesa) ni con `customers`, pero su token SÍ es válido en la misma ruta
  `/fermento/{token?}` (ver `EventController::fermento()`). El `status`
  se edita a mano en `/influencers/admin` en base a lo coordinado por
  WhatsApp — no hay flujo de confirmación en la web. `influencer_posts`
  guarda el registro manual de resultados (post/story/reel/video con
  métricas) de cada influencer, cargado a mano desde ese mismo panel —
  no hay integración con Instagram/TikTok
- Dos sistemas de bloqueo de sitio, independientes y con exclusiones
  propias:
  - `MaintenanceMode` middleware (`MAINTENANCE_MODE` en .env,
    `config/maintenance.php`): muro de mantenimiento general, cookie
    de acceso `clan_access` con palabra clave `clandestino`, vista en
    `/mantenimiento`
  - `ClanPreholder` middleware (`CLAN_PREHOLDER_ACTIVE` en .env,
    `config/preholder.php`): pantalla "Estamos atizando nuestros
    fogones"
  - Ambos excluyen explícitamente `/showclinic*`, `/intimo*`,
    `/fermento*`, `/reservas/*` e `/influencers/*` (ver
    `ClanPreholder::handle` y `MaintenanceMode::handle`) — nunca deben
    bloquearse esas rutas
- Fuentes self-hosteadas (no Google Fonts CDN):
  - CLAN: Poppins (`public/assets/fonts/poppins/` y
    `.../clan/poppins/`), Cinzel (`public/assets/fonts/cinzel/`),
    Cinzel Decorative (`public/assets/fonts/clan/`)
  - Show Clinic: Montserrat y "AmoretSans" (The Amoret Collection
    Sans) en `public/assets/showclinic/fonts/`
  - Fermento: Canela (Medium) e Inter en `public/assets/fonts/canela/`
    y `.../inter/`
  - Playfair Display Medium Italic en `public/assets/fonts/playfair/`
    (descargada de Google Fonts y autohospedada) quedó del primer
    intento de landing propia para influencers, que se descartó — hoy
    no la usa ninguna vista, se dejó por si sirve para el diseño en
    Figma del preloader personalizado de influencers

## Gotchas técnicos
- Linux (producción) es sensible a mayúsculas/minúsculas en nombres
  de archivo — Windows (desarrollo local) no. Verificar nombres de
  componentes Blade exactos antes de deploy
- Variables de entorno de producción viven en el panel de Laravel
  Cloud, NO en el .env local — no se sincronizan automáticamente
- Deploys a producción requieren correr migraciones manualmente vía
  Laravel Cloud → Commands: php artisan migrate --force

## Estado actual (actualizar conforme avance el proyecto)
[Deja este espacio para que anotes en qué se quedó cada feature]
