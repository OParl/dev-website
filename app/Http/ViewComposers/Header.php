<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;

class Header
{
    /**
     * The header sections.
     *
     * Format:
     * 
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
        [
            'title'    => 'app.endpoints.title',
            'routeKey' => 'endpoints',
        ],
        [
            'routeKey' => 'contact',
            'title'    => 'app.contact.title',
            'icon'     => 'fa-comment',
        ],
    ];
    /**
     * @var Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function compose(View $view)
    {
        return $view->with('sections', $this->getSections($this->router));
    }

    protected function getSections(Router $router)
    {
        $currentRouteName = $router->currentRouteName();

        return collect($this->sections)->map(function ($section) use ($currentRouteName) {
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
    }
}
