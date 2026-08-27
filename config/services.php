<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'showclinic' => [
        'admin_password' => env('SHOWCLINIC_ADMIN_PASSWORD'),
    ],

    'reservas' => [
        'admin_password' => env('RESERVAS_ADMIN_PASSWORD'),
        'whatsapp_number' => env('RESERVAS_WHATSAPP_NUMBER', '51932817621'),
        // Panel de solo revisión (reservas ya confirmadas), contraseña propia
        // e independiente de admin_password — pensado para compartir con
        // FORNO/MOLTO sin darles acceso a editar/eliminar/confirmar pagos.
        // El default 'fermento' queda commiteado a propósito (así pedido);
        // para mayor privacidad basta con setear RESERVAS_REVIEW_PASSWORD en .env.
        'review_password' => env('RESERVAS_REVIEW_PASSWORD', 'fermento'),
    ],

];
