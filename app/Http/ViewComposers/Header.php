<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class Header
{
    protected $sections = [
        [
            'routeKey' => 'about',
            'title' => 'Über OParl'
        ],

        [
            'routeKey' => 'news',
            'title' => 'Aktuelles'
        ],

        [
            'routeKey' => 'specification',
            'title' => 'Spezifikation'
        ],

        [
            'routeKey' => 'downloads',
            'title' => 'Downloads'
        ],

//      [
//        'title' => 'Demoserver',
//        'url' => 'http://demoserver.oparl.org/'
//      ],

        [
            'routeKey' => 'status',
            'title' => 'Status'
        ],

        [
            'routeKey' => 'newsletter',
            'title' => 'Newsletter'
        ],

        [
            'routeKey' => 'imprint',
            'title' => 'Impressum'
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
                'title' => '<span class="glyphicon glyphicon-user"></span>'
            ]);
        }

        return $sections;
    }
}
