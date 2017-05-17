<?php

namespace App\Http\ViewComposers;

class DevelopersHeader extends Header
{
    /**
    <li class="col-xs-12 col-sm-2"><a href="{{ route('downloads.index') }}">@lang('app.downloads.title')</a></li>
    <li class="col-xs-12 col-sm-2"><a href="{{ route('endpoints.index') }}">@lang('app.endpoints.title')</a></li>

     *
     */
    protected $sections = [
        [
            'title'    => 'app.downloads.title',
            'routeKey' => 'downloads',
        ],
        [
            'title'    => 'app.endpoints.title',
            'routeKey' => 'endpoints',
        ],
    ];
}