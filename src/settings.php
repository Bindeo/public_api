<?php

return [
    'settings' => [
        'displayErrorDetails' => true,

        // Monolog settings
        'logger'              => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../var/logs/app.log',
        ],

        // MySQL
        'mysql'               => [
            'host'   => 'mysql.bindeo.com',
            'user'   => 'API',
            'pass'   => 'a1607b03e86453ebaf35bec81b4194ae',
            'scheme' => 'API'
        ],

        // Private API
        'api'                 => [
            'url'   => 'private.bindeo.com',
            'token' => '5f20ba1d9f033c18e8c930e2f82678a1'
        ]
    ]
];