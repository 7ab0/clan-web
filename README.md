# Clan Web

Sitio web del restaurante Clan, construido sobre Laravel 12.

## Qué es este proyecto

`clan-web` es el sitio institucional/marketing de Clan. Se partió de una plantilla
comercial de restaurante (Elegencia, ver abajo) para acelerar el desarrollo del
front-end, y se está adaptando progresivamente a la marca e identidad visual de Clan
(logos, paleta de colores, textos y estructura de vistas).

**Estado actual:** se corrió el setup inicial (dependencias instaladas, entorno local
configurado, marca renombrada y paleta de color de acento actualizada). La estructura
de vistas del template original todavía no fue reescrita — eso es trabajo pendiente.

## Plantilla base

- **Nombre:** Elegencia — Resort & Restaurant (Laravel 12 template)
- **Origen:** `elegencia-resort-restaurant-laravel-12-template-2025-12-29-13-30-32-utc.zip`
- **Subcarpeta usada:** `Elegencia/restaurant` (la variante de restaurante del template;
  no se usó la variante `hotel-resort`)
- **Autores originales del theme:** Thememarch (JS) / Parv Infotech (SCSS), según los
  créditos dejados en los comentarios de cabecera de `public/assets/js/main.js` y
  `public/assets/sass/style.scss`

## Requisitos

- PHP 8.4 (se usa el PHP embebido de [Laravel Herd](https://herd.laravel.com))
- Composer
- Node.js + npm

## Comandos de setup

```bash
# Instalar dependencias
composer install
npm install

# Variables de entorno (ya versionado un .env.example de referencia)
cp .env.example .env
php artisan key:generate

# Base de datos (SQLite para desarrollo local)
touch database/database.sqlite   # si no existe ya
php artisan migrate

# Levantar el sitio (ver nota de Windows abajo)
herd link clan-web
# o, en Mac/Linux, o si no usas Herd:
php artisan serve

# Compilar assets (Vite)
npm run dev      # desarrollo con hot reload
npm run build    # build de producción
```

El sitio queda disponible en `http://clan-web.test` (Herd) o `http://127.0.0.1:8000`
(`php artisan serve`), según el método usado.

> **Nota (Windows):** en este entorno, `php artisan serve` falla con
> `Failed to listen on 127.0.0.1:8000 (reason: ?)`. La causa es un bug conocido de
> `Illuminate\Foundation\Console\ServeCommand`: filtra las variables de entorno del
> proceso hijo comparando nombres exactos como `PATH`/`SYSTEMROOT`, pero en Windows
> esas variables se llaman `Path`/`SystemRoot` (case distinto), así que terminan
> eliminadas del proceso hijo y el bind del socket falla. Usar `herd link` (o
> `php -S 127.0.0.1:8000 -t public public/index.php` como alternativa manual) evita
> el problema por completo.

### Base de datos local

El proyecto usa **SQLite** para desarrollo local (`DB_CONNECTION=sqlite` en `.env`,
archivo en `database/database.sqlite`, ignorado por git). No requiere levantar MySQL/Postgres
para trabajar localmente.

### Base de datos en producción (Laravel Cloud)

En producción se usa **MySQL** en vez de SQLite — la infraestructura de contenedores
efímeros de Laravel Cloud no persiste el archivo `.sqlite` entre comandos. La conexión
`mysql` ya está configurada en `config/database.php` vía las variables de entorno
estándar (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, ver
`.env.example`); la base de datos y esas variables se crean y configuran directo en el
panel de Laravel Cloud, no en este repo.

## Estructura de carpetas

```
clan-web/
├── app/            # Código de la aplicación (Models, Controllers, etc.)
├── bootstrap/       # Bootstrap del framework
├── config/          # Configuración de Laravel
├── database/        # Migraciones, seeders, factories y database.sqlite
├── public/           # Punto de entrada web y assets estáticos del template
│   └── assets/
│       ├── css/      # CSS compilado del template (style.css)
│       ├── sass/     # Fuente SCSS del template (_color_variable.scss, etc.)
│       ├── js/        # JS del template (main.js)
│       └── img/       # Imágenes e íconos, incluye los logos de Clan
├── resources/
│   ├── views/         # Vistas Blade (layout heredado del template, aún sin adaptar)
│   ├── css/            # Entry point de Vite (resources/css/app.css)
│   └── js/             # Entry point de Vite (resources/js/app.js)
├── routes/            # Rutas web
├── storage/            # Logs, cache, archivos generados
├── tests/               # Tests
├── vite.config.js       # Configuración de Vite (compila resources/css y resources/js)
└── README.md
```

## Pendientes conocidos

- Reescribir/adaptar la estructura de las vistas Blade a la identidad y contenidos reales de Clan.
- Actualizar el `src` de los logos en `resources/views/components/footer.blade.php` y
  `resources/views/components/head.blade.php` / `header.blade.php` para apuntar a los
  logos de Clan ya copiados en `public/assets/img/` (`Logo Clan.png`, `Icono Clan.png`,
  `Clan Logo.svg`).
- Conectar producción / definir hosting (no hecho todavía, deliberadamente).

### Fotografía pendiente

El único material fotográfico real de Clan disponible hasta ahora son 3 fotos
(`_A7_7375.jpg`, `_A7_7416.jpg`, `Carta.jpg`), ya usadas como hero de home, hero de
home2 y fondo de la sección Appetizers de `/menu` (ver `public/assets/img/clan-hero-*.jpg`
y `clan-carta-raiz.jpg`). **No hay todavía fotografía de platos individuales**, así que
los siguientes placeholders del template se dejaron intactos a propósito — están
marcados con comentarios `TODO(Clan)` en el código correspondiente y **no deben
reemplazarse con imágenes genéricas del template**, solo con fotografía real cuando
esté disponible:

- Íconos por plato en `/menu` (`item-show.png`, `item-show_2.png`, `item-show_3.png` —
  se repiten ~30 veces en `resources/views/home/menu.blade.php`).
- Fondos de las secciones Desserts y Bar Items en `/menu` (`bessertsMenuBg.png`,
  `barMenuBg.png`).
- Hero de home3 (`hero_bg_3.jpg`, variante de home no usada como ruta principal).
- Banner compartido de todas las subpáginas (`banner_top_all.png`, en
  `resources/views/components/hero.blade.php`).
- Sección "Food Showcase" de home (`gallery_1.jpg` etc.) y las fotos de platos en
  home2/portfolio (`food_item_1.jpg`–`food_item_5.jpg`).
