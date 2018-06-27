<?php

return [
    'specificationBuildMode' => env('OPARL_BUILD_MODE', 'native'),

    /*
     * These constraints are used for the site-internal functions like displaying
     * the specification's web view or providing the validation service.
     */
    'versions'               => [
        'specification' => [
            '1.0' => '~1.0',
            '1.1'    => '~1.1',
            'latest'    => 'master',
        ],
        'liboparl'      => [
            'stable' => '~0.4',
            'latest' => 'master',
        ],
        'validator'     => [
            'stable' => 'production',
            'latest' => 'master',
        ],
    ],

    /*
     * These constraints are used to provide downloads for the specified component
     */
    'downloads'              => [
        'specification' => [
            '~1.0',
            '~1.1',
            'master',
        ],
    ],

    /*
     * Mapping of Schema endpoints to version constraints of the specification repository
     */
    'schema'                 => [
        '1.0'    => '~1.0',
        '1.1'    => '~1.1',
        'master' => 'master',
    ],

    /*
     * List of allowed repositories for the update hooks
     */
    'repositories'           => [
        'github' => [
            'spec',
            'dev-website',
            'validator',
            'resources',
            'liboparl',
        ],
    ],

    /*
     * liboparl related configuration
     */
    'liboparl'               => [
        'prefix' => env('LIBOPARL_PREFIX'), // install prefix, MUST be set in the environment
    ],
];
