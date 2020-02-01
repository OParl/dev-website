<?php

use App\Services\Serializer;
use EFrane\Transfugio\Transformers\Formatter\DateISO8601;
use EFrane\Transfugio\Transformers\Formatter\DateTimeISO8601;
use EFrane\Transfugio\Transformers\Formatter\EMailURI;
use EFrane\Transfugio\Transformers\Formatter\HttpURI;

return [
    'itemsPerPage'   => 100,
    'rootURL'        => 'api/oparl/v1/',
    'modelNamespace' => 'App\\Model\\OParl10',

    'transformers' => [
        'serializer' => Serializer::class,

        'namespace'    => 'App\Http\Transformers\OParl\V10',
        'classPattern' => '[:modelName]Transformer',

        'formatHelpers' => [
            'email'    => EMailURI::class,
            'date'     => DateISO8601::class,
            'datetime' => DateTimeISO8601::class,
            'url'      => HttpURI::class,
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
        'documentationRoot' => '/storage/app/schema/1.0',
    ],
];
