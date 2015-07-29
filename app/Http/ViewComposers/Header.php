<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class Header
{
  protected $sections = [
//      [
//        'routeKey' => 'news',
//        'title' => 'Aktuelles'
//      ],

      [
        'routeKey' => 'specification',
        'title' => 'Spezifikation'
      ],

      [
        'routeKey' => 'downloads',
        'title' => 'Downloads'
      ],

      [
        'title' => 'Demoserver',
        'url' => 'http://demoserver.oparl.org/'
      ],

//      [
//        'routeKey' => 'status',
//        'title' => 'Status'
//      ],
//
//      [
//        'routeKey' => 'newsletter',
//        'title' => 'Newsletter'
//      ],
//
      [
        'routeKey' => 'imprint',
        'title' => 'Impressum'
      ],
    ];

  protected function getSections()
  {
    $sections = $this->sections;
    $currentRouteName = \Route::currentRouteName();

    foreach ($sections as $key => $section)
    {
      if (isset($section['routeKey']) && starts_with($currentRouteName, $section['routeKey']))
      {
        $sections[$key]['current'] = true;
        break;
      }
    }

    return $sections;
  }

  public function compose(View $view)
  {
    return $view->with('sections', $this->getSections());
  }
}