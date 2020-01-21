<?php

return [
    'specificationBuildMode' => env('OPARL_BUILD_MODE', 'native'),

    /*
     * The default displayed version of the specification at /spezifikation
     */
    'specificationDisplayVersion' => '1.1',

    /*
     * These constraints are used for the site-internal functions like displaying
     * the specification's web view or providing the validation service.
     */
    'versions'               => [
        'specification' => [
            '1.0'    => '1.0.*',
            '1.1'    => '1.1.*',
            'master' => '*',
        ],
        'liboparl'      => [
            'stable' => '0.4.*',
            'latest' => '*',
        ],
        'validator'     => [
            'stable' => 'production',
            'latest' => 'master',
        ],
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
