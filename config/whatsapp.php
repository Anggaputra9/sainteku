<?php

return [
    'driver'  => env('WHATSAPP_DRIVER', 'whatsar'),
    'enabled' => env('WHATSAPP_ENABLED', true),

    'whatsar' => [
        'url'             => env('WHATSAR_URL', 'http://127.0.0.1:8080'),
        'api_key'         => env('WHATSAR_API_KEY'),
        'default_session' => env('WHATSAR_DEFAULT_SESSION'),
        'timeout'         => (int) env('WHATSAR_TIMEOUT', 30),
    ],

    'fonnte' => [
        'token'    => env('FONNTE_TOKEN'),
        'base_url' => env('FONNTE_BASE_URL', 'https://api.fonnte.com'),
    ],
];