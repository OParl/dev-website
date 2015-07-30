<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Model\Post;
use App\Model\Comment;

class DashboardController extends Controller
{
  public function index()
  {
    return view('admin.dashboard', [
      'post' => [
        'all' => Post::count(),
        'published' => Post::published()->count(),
        'drafts' => Post::draft()->count(),
        'scheduled' => Post::scheduled()->count()
      ],
      'comment' => [
        'all' => Comment::count(),
        'ham' => Comment::ham()->count(),
        'spam' => Comment::spam()->count(),
        'unvalidated' => Comment::unvalidated()->count()
      ]
    ]);
  }
}