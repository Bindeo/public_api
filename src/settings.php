<?php

return [
    'settings' => [
        'displayErrorDetails' => true,

        // Monolog settings
        'logger'              => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../var/logs/app.log',
        ],

        // OAuth keys
        'oauth'               => [
            'keys' => [
                'private' => '/var/www/keys/api.key',
                'public'  => '/var/www/keys/api'
            ]
        ],

        // Private API
        'api'                 => [
            'url'   => 'private.bindeo.com',
            'token' => 'd5f14b4a435a5ef685bbaedbdd49de9fa7bd728344451113b15e9b0fd29e183a'
        ]
    ]
];