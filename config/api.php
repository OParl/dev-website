<?php

return [
    'default' => 'demo',

    'demo' => [
        'prefix'     => 'oparl_',
        'connection' => 'db.connection.demo_default',
    ],

    'prod' => [
        'prefix'     => 'oparl_',
        'connection' => 'sqlite_prod',
    ],
];
