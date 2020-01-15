<?php

namespace App\Http\ViewComposers;

class DevelopersHeader extends Header
{
    protected $sections = [
        [
            'title'    => 'app.downloads.title',
            'routeKey' => 'downloads',
        ],
        [
            'title'    => 'app.endpoints.title',
            'routeKey' => 'endpoints',
        ],
//        [
//            'title'    => 'app.validation.title',
//            'routeKey' => 'validator',
//        ],
    ];
}
