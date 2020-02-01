<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class Header
{
    public function compose(View $view)
    {
        return $view->with('sections', $this->getSections());
    }

    /**
     * The header sections.
     *
     * Format:
     * <code>
     * [
     *      'icon'     => Displayed link icon (REQUIRED)
     *      'title'    => Displayed link title (REQUIRED)
     *      'url'      => External link
     *      'params'   => Route parameters, can be used instead of refering to an index route
     *      'routeKey' => Key to a defined route, $routeName.index will be auto-expanded
     * ]
     * </code>
     *
     * @var array header config
     */
    protected $sections = [
        [
            'routeKey' => 'developers',
            'title'    => 'app.developers.title',
            'icon'     => 'fa-home',
        ],
        [
            'routeKey' => 'specification',
            'title'    => 'app.specification.title',
            'icon'     => 'fa-book',
        ],
        [
            'title'    => 'app.downloads.title',
            'routeKey' => 'downloads',
        ],
//        [
//            'title'    => 'app.validation.title',
//            'routeKey' => 'validator',
//        ],
        [
            'title'    => 'app.endpoints.title',
            'routeKey' => 'endpoints',
        ],
//        [
//            'routeKey' => 'api.oparl',
//            'title'    => 'app.demo.title',
//            'icon'     => 'fa-dashboard',
//        ],
        [
            'routeKey' => 'contact',
            'title'    => 'app.contact.title',
            'icon'     => 'fa-comment',
        ],
    ];

    protected function getSections()
    {
        $currentRouteName = \Route::currentRouteName();

        $sections = collect($this->sections)->map(function ($section) use ($currentRouteName) {
            if (isset($section['routeKey']) && Str::startsWith($currentRouteName, $section['routeKey'])) {
                $section['current'] = true;
            }

            if (isset($section['routeKey']) && !isset($section['params'])) {
                $section['href'] = route($section['routeKey'].'.index');
            } elseif (isset($section['params'])) {
                $section['href'] = route($section['routeKey'], $section['params']);
            } else {
                $section['href'] = $section['url'];
            }

            return $section;
        })->toArray();

        return $sections;
    }
}
