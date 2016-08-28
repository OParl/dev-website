<?php return [
    'pathmap' => [
        // configure route => database prefix matching

        /*
        'route-prefix' => [
            'prefix' => 'table-prefix in the scope of the database',
            'db' => 'connection name from database.php',
        ],
        */

        'dev.%HOSTNAME%/api/v1/' => [
            'prefix' => 'oparl_',
            'db' => 'sqlite_demo',
        ],

        'api/v1' => [
            'prefix' => '',
            'db' => 'sqlite_',
        ]
    ]
];
