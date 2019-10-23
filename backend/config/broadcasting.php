<?php

    return [
        'default' => 'main',

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_CLUSTER'),
                'encrypted' => true,
            ]
        ],


        'connections' => [
            'driver' => 'pusher',
            'pusher' => [
                'driver' => 'pusher',
                'key' => env('PUSHER_APP_KEY'),
                'secret' => env('PUSHER_APP_SECRET'),
                'app_id' => env('PUSHER_APP_ID'),
                'options' => [
                    'cluster' => env('PUSHER_CLUSTER'),
                    'encrypted' => false,
                ]
            ],
            'main' => [
                'driver' => 'pusher',
                'key' => env('PUSHER_APP_KEY'),
                'secret' => env('PUSHER_APP_SECRET'),
                'app_id' => env('PUSHER_APP_ID'),
                'options' => [
                    'cluster' => env('PUSHER_CLUSTER'),
                    'encrypted' => false,
                ],
                'host' => null,
                'port' => null,
                'timeout' => null,
            ],

            'alternative' => [
                'auth_key' => 'your-auth-key',
                'secret' => 'your-secret',
                'app_id' => 'your-app-id',
                'options' => [],
                'host' => null,
                'port' => null,
                'timeout' => null,
            ],

        ],
    ];
