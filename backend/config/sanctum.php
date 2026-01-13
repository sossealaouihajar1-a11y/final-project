<?php

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,localhost:3000,localhost:5173,localhost:8000,127.0.0.1,127.0.0.1:3000,127.0.0.1:5173,127.0.0.1:8000,::1')),
    'guard' => ['web'],
    'expiration' => null,
    'token_prefix' => '',
    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
    ],
];