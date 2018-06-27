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

    'slack' => [
        'ci' => [
            'enabled'  => env('SLACK_ENABLED', false),
            'endpoint' => env('SLACK_ENDPOINT', ''),
            'channel'  => env('SLACK_CHANNEL_CI', '#ci'),
        ],
        'validation' => [
            'enabled'  => env('SLACK_ENABLED', false),
            'endpoint' => env('SLACK_ENDPOINT', ''),
            'channel'  => env('SLACK_CHANNEL_VALIDATOR', '#feedback'),
        ],
    ],
];
