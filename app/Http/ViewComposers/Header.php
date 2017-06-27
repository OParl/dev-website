<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class Header
{
    /**
     * The header sections
     *
     * Format:
     * <code>
     * [
     *      'routeKey' => Key to a defined route, $routeName.index will be auto-expanded
     *      'title'    => Displayed link title (REQUIRED)
     *      'url'      => External link
     *      'params'   => Route parameters, can be used instead of refering to an index route
     * ]
     *
     * @var array header config
     */
    protected $sections = [
        [
            'routeKey' => 'developers',
            'title'    => 'app.developers.title',
        ],

        [
            'routeKey' => 'specification',
            'title'    => 'app.specification.title',
        ],

        [
            'routeKey' => 'api.oparl',
            'title'    => 'app.demo.title',
        ],

        [
            'routeKey' => 'contact',
            'title'    => 'app.contact.title',
        ],
    ];

    public function compose(View $view)
    {
        return $view->with('sections', $this->getSections());
    }

    protected function getSections()
    {
        $currentRouteName = \Route::currentRouteName();

        $sections = collect($this->sections)->map(function ($section) use ($currentRouteName) {
            if (isset($section['routeKey']) && starts_with($currentRouteName, $section['routeKey'])) {
                $section['current'] = true;
            }

            if (isset($section['routeKey']) && !isset($section['params'])) {
                $section['href'] = route($section['routeKey'] . '.index');
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
