<?php

return [

    'defaults' => [
        'guard' => 'admin',
        'passwords' => 'admins',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // ⬇️ Guard khusus admin
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // ⬇️ Provider untuk tabel / model Admin
        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        // ⬇️ TAMBAHAN UNTUK RESET PASSWORD ADMIN
        'admins' => [
            'provider' => 'admins',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];