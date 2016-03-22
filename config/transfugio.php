<?php return [
    'itemsPerPage'   => 100,
    'rootURL'        => 'api/v1/',
    'modelNamespace' => 'OParl\\Server\\Model\\',

    'transformers' => [
        'serializer' => 'EFrane\Transfugio\Transformers\SanitizedDataArraySerializer',

        'namespace'    => 'App\Handlers\Transformers',
        'classPattern' => '[:modelName]Transformer',

        'formatHelpers' => [
            'email' => 'EFrane\Transfugio\Transformers\Formatter\EMailURI',
            'date'  => 'EFrane\Transfugio\Transformers\Formatter\DateISO8601',
            'url'   => 'EFrane\Transfugio\Transformers\Formatter\HttpURI',
        ],

        'recursionLimit' => 2,
    ],

    'http' => [
        'format'     => 'json_accept',
        'enableCORS' => true,
    ],

    'web' => [
        'documentationType' => 'JSONSchema',
        'documentationRoot' => '/resources/assets/schema',
    ],
];
