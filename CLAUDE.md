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
  — flujo de reserva y pago de Íntimo
- /showclinic — landing standalone del evento (pre-holder personalizado
  vía ?inv=CODIGO, countdown, RSVP, música de fondo)
- /showclinic/admin — panel de gestión de invitados (login propio,
  protegido por middleware `showclinic.admin` / `SHOWCLINIC_ADMIN_PASSWORD`)

## Convenciones importantes
- Show Clinic NO hereda el layout/nav de CLAN — tiene su propia
  identidad visual completa. Íntimo SÍ extiende `layout.layout` pero
  oculta los elementos genéricos del template vía CSS — ten cuidado al
  modificar `layout.layout`, afecta tanto al sitio CLAN como a Íntimo
- La tabla `guests` es de Show Clinic (code, name, profession,
  compliment, status RSVP: pendiente/confirmado/rechazado, plus_one,
  companion_name). La tabla `intimo_guests` es de Íntimo (event_id,
  token único de 12 chars, opened_at, whatsapp_sent_at) — nombres
  similares pero esquemas distintos, NO confundir ni fusionar
- Dos sistemas de bloqueo de sitio, independientes y con exclusiones
  propias:
  - `MaintenanceMode` middleware (`MAINTENANCE_MODE` en .env,
    `config/maintenance.php`): muro de mantenimiento general, cookie
    de acceso `clan_access` con palabra clave `clandestino`
  - `ClanPreholder` middleware (`CLAN_PREHOLDER_ACTIVE` en .env,
    `config/preholder.php`): pantalla "Estamos atizando nuestros
    fogones"
  - Ambos excluyen explícitamente `/showclinic*`, `/intimo*` y
    `/reservas/*` (ver `ClanPreholder::handle`) — nunca deben
    bloquearse esas rutas
- Fuentes self-hosteadas (no Google Fonts CDN):
  - CLAN: Poppins (`public/assets/fonts/poppins/` y
    `.../clan/poppins/`), Cinzel (`public/assets/fonts/cinzel/`),
    Cinzel Decorative (`public/assets/fonts/clan/`)
  - Show Clinic: Montserrat y "AmoretSans" (The Amoret Collection
    Sans) en `public/assets/showclinic/fonts/`

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
