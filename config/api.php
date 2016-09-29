<?php

return [
    'default' => 'demo',

    'demo' => [
        'prefix'     => 'oparl_',
        'connection' => 'sqlite_demo',
    ],

    'prod' => [
        'prefix'     => '',
        'connection' => 'sqlite_prod',
    ],
];
