<?php

return [
    'itemsPerPage'   => 100,
    'rootURL'        => 'api/v1/',
    'modelNamespace' => 'OParl\\Server\\Model\\',

    'transformers' => [
        'serializer' => OParl\Server\API\Serializer::class,

        'namespace'    => 'OParl\\Server\\API\\Transformers',
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
        'documentationEnabled' => true, // Toggle the auto-documentation feature

        'documentationType' => 'JSONSchema',
        'documentationRoot' => '/storage/app/hub_sync/oparl_spec/schema/',
    ],
];
