<?php

return [
    'specificationBuildMode' => env('OPARL_BUILD_MODE', 'native'),

    'versions' => [
        'specification' => [
            'stable' => '~1.0',
            'latest' => 'master',
        ],
        'liboparl' => [
            'stable' => '~0.2',
            'latest' => 'master'
        ],
        'validator' => [
            'stable' => '~0.1',
            'latest' => 'master'
        ]
    ],

    'downloads' => [
        'specification' => [
            '~1.0',
            'master'
        ]
    ]
];
