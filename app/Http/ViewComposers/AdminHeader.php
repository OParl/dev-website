<?php namespace App\Http\ViewComposers;

use App\Model\Comment;

use Illuminate\Contracts\View\View;

class AdminHeader
{
  public function compose(View $view)
  {
    $currentRoute = \Route::currentRouteName();

    $spamCount = Comment::spam()->count();

    $sections = [
      [
        'current' => starts_with($currentRoute, 'admin.dashboard'),
        'route' => 'admin.dashboard',
        'title' => 'Übersicht'
      ],
      [
        'current' => starts_with($currentRoute, 'admin.news'),
        'route' => 'admin.news.index',
        'title' => 'Nachrichten'
      ],
      [
        'current' => starts_with($currentRoute, 'admin.comments'),
        'route' => 'admin.comments.index',
        'title' => ($spamCount > 0) ? 'Kommentare <span class="badge">'.Comment::spam()->count().'</span>'
                                    : 'Kommentare'
      ],
    ];

    return $view->with('sections', $sections);
  }
}