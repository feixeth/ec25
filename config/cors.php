<?php


return [
    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:5173',
        'http://ec25.ddev.site',
    ],
    'allowed_headers' => [
        'Content-Type', 
        'X-Requested-With', 
        'Authorization', 
        'X-CSRF-TOKEN'
    ],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];