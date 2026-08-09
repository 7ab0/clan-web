<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pre-holder de CLAN
    |--------------------------------------------------------------------------
    |
    | Toggle via CLAN_PREHOLDER_ACTIVE en .env (true/false). Cuando esta
    | activo, todas las rutas del sitio muestran la pantalla de espera
    | "Estamos atizando nuestros fogones" en vez del contenido real,
    | EXCEPTO /showclinic y sus sub-rutas (evento de otro cliente, con
    | su propia identidad, no debe verse afectado).
    |
    */

    'active' => env('CLAN_PREHOLDER_ACTIVE', false),

];
