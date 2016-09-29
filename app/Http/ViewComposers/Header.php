<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class Header
{
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
            'routeKey' => 'api.v1.system.index',
            'params'   => ['format' => 'html'],
            'title'    => 'app.demo.title',
        ],

        [
            'title'    => 'app.contact.title',
            'routeKey' => 'contact',
        ],
    ];

    public function compose(View $view)
    {
        return $view->with('sections', $this->getSections());
    }

    protected function getSections()
    {
        $sections = $this->sections;
        $currentRouteName = \Route::currentRouteName();

        foreach ($sections as $key => $section) {
            if (isset($section['routeKey']) && starts_with($currentRouteName, $section['routeKey'])) {
                $sections[$key]['current'] = true;
                break;
            }
        }

        if (\Auth::check()) {
            array_unshift($sections, [
                'routeKey' => 'admin.dashboard',
                'title'    => '<span class="glyphicon glyphicon-user"></span>',
            ]);
        }

        return $sections;
    }
}
