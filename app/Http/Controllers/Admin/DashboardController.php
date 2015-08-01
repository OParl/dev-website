<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Jobs\UpdateLiveCopy;
use App\Jobs\UpdateVersionHashes;
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

  public function update($what)
  {
    $message = 'Das %supdate wurde erfolgreich gestartet.';

    switch ($what)
    {
      case 'livecopy':
        $this->dispatch(new UpdateLiveCopy);
        $message = sprintf($message, 'Livekopie');
        break;

      case 'versions':
        $this->dispatch(new UpdateVersionHashes);
        $message = sprintf($message, 'Versionslisten');
        break;

      default:
        throw new \InvalidArgumentException("Unknown.");
    }

    return redirect()->route('admin.dashboard.index')->with('info', $message);
  }
}