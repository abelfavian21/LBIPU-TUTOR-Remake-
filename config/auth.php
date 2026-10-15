<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'tutors',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'tutors',
        ],
    ],

    'providers' => [
        'tutors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Tutor::class,
        ],
    ],

    'passwords' => [
        'tutors' => [
            'provider' => 'tutors',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
