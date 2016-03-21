<?php

return [
    'settings' => [
        'displayErrorDetails' => true,

        // Monolog settings
        'logger'              => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../var/logs/app.log',
        ],

        // Storage for private OAuth apps
        'oauth'               => [
            '75be9f5e5234406d544d84e32e1747e4' => [
                'grantType' => 'client_credentials',
                'clientId'  => '1',
                'appName'   => 'front',
                'appRole'   => 'all'
            ]
        ],

        // MySQL
        'mysql'               => [
            'host'   => 'mysql.bindeo.com',
            'user'   => 'API',
            'pass'   => 'a1607b03e86453ebaf35bec81b4194ae',
            'scheme' => 'API'
        ]
    ]
];