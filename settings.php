<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'debug' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => env('DB_NAME'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        'kudago_api' => 'https://kudago.com/public-api/v1.3/'
    ],
    'token' => env('TELEGRAM_BOT_API_TOKEN'),

];