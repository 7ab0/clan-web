<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    |
    | Toggle via MAINTENANCE_MODE in .env (true/false). When enabled, every
    | route is redirected to the maintenance view unless the visitor already
    | holds a valid access cookie.
    |
    */

    'enabled' => env('MAINTENANCE_MODE', false),

    'access_cookie' => 'clan_access',

    'access_word' => 'clandestino',

];
