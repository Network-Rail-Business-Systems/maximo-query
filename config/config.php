<?php

return [
    'emulator' => env('MAXIMO_EMULATOR', false),

    'maximo_url' => env('MAXIMO_URL', 'http://localhost/maximo'),

    'cookie_cache_key' => env('MAXIMO_KEY', 'maximo-query:cookies'),

    'maximo_username' => env('MAXIMO_USERNAME', 'username'),

    'maximo_password' => env('MAXIMO_PASSWORD', 'password'),

    'cache_ttl_minutes' => env('MAXIMO_TTL', 60)
];
