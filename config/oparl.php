<?php

return [
    'specificationBuildMode' => env('OPARL_BUILD_MODE', 'native'),

    /*
     * These constraints are used for the site-internal functions like displaying
     * the specification's web view or providing the validation service.
     */
    'versions'               => [
        'specification' => [
            'stable' => '~1.0',
            'latest' => 'master',
        ],
        'liboparl'      => [
            'stable' => '~0.2',
            'latest' => 'master',
        ],
        'validator'     => [
            'stable' => '~0.1',
            'latest' => 'master',
        ],
    ],

    /*
     * These constraints are used to provide downloads for the specified component
     */
    'downloads'              => [
        'specification' => [
            '~1.0',
            'master',
        ],
    ],

    /*
     * Mapping of Schema endpoints to version constraints of the specification repository
     */
    'schema'                 => [
        '1.0'    => '~1.0',
        '1.1'    => 'master',
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
        ],
        'gitlab' => [
            'grindhold-liboparl',
        ],
    ],

    /*
     * liboparl related configuration
     */
    'liboparl' => [
        'prefix' => env('LIBOPARL_PREFIX'), // install prefix, MUST be set in the environment
    ],
];
