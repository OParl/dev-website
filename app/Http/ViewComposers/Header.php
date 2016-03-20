<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class Header
{
    protected $sections = [
        [
            'title' => 'Über OParl',
            'url'   => 'https://oparl.org/ueber-oparl/',
        ],

        [
            'routeKey' => 'specification',
            'title'    => 'Spezifikation',
        ],

        [
            'routeKey' => 'api',
            'title'    => 'Demo',
        ],

        [
            'title' => 'Kontakt',
            'url'   => 'https://oparl.org/kontakt/',
        ],

//      [
//        'title' => 'Demoserver',
//        'url' => 'http://demoserver.oparl.org/'
//      ],
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
