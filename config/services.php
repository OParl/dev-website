<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe'    => [
        'model'  => 'App\User',
        'key'    => '',
        'secret' => '',
    ],

    // buildkite service config for efrane/buildkite
    'buildkite' => [
        'project'      => env('BUILDKITE_PROJECT', ''),
        'access_token' => env('BUILDKITE_ACCESS_TOKEN', ''),
    ],

    // akismet service config for efrane/akismet
    'akismet'   => [
        'key' => env('AKISMET_KEY'),
    ],

    'repositories' => [
        'spec' => [
            'user'       => 'OParl',
            'repository' => 'spec',
        ],
    ],

    'slack' => [
        'ci' => [
            'enabled'  => env('SLACK_ENABLED', false),
            'endpoint' => env('SLACK_ENDPOINT', ''),
            'channel'  => env('SLACK_CHANNEL', '#ci'),
        ],
    ],

    'gitlab' => [
        'token' => env('GITLAB_WEBHOOK_SECRET', ''),
    ],
];
