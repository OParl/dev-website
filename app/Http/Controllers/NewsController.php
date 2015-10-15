<?php namespace App\Http\Controllers;

use App\Jobs\ValidateComment;
use App\Model\Comment;
use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Model\Post;
use App\Model\Tag;
use App\Http\Requests\NewCommentRequest;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::published()->paginate(15);
        $title = 'Aktuelles';

        return view('news.index', compact('posts', 'title'));
    }

    public function tag($tag)
    {
        $posts = Post::published()->whereHas('tags', function ($query) use ($tag) {
      return $query->whereSlug($tag);
    })->paginate(15);

        $tag = Tag::whereSlug($tag);

        $title = $tag->name.' - Aktuelles';

        return view('news.index', compact('posts', 'title'));
    }

    public function yearly($year)
    {
        $start = Carbon::createFromDate($year)->startOfYear();
        $end   = $start->copy()->endOfYear();

        $posts = Post::published()->whereBetween('published_at', [$start, $end])->orderBy('published_at')->paginate(15);

        $title = $year . ' - Aktuelles';

        return view('news.index', compact('posts', 'title'));
    }

    public function monthly($year, $month)
    {
        $start = Carbon::createFromDate($year, $month)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $posts = Post::published()->whereBetween('published_at', [$start, $end])->orderBy('published_at')->paginate(15);

        $title = $start->format('M Y') . ' - Aktuelles';

        return view('news.index', compact('posts', 'title'));
    }

    public function daily($year, $month, $day)
    {
        $start = Carbon::createFromDate($year, $month, $day)->startOfDay();
        $end   = $start->copy()->endOfDay();

        $posts = Post::published()->whereBetween('published_at', [$start, $end])->orderBy('published_at')->paginate(15);

        $title = $start->format('d. M Y') . ' - Aktuelles';

        return view('news.index', compact('posts', 'title'));
    }

    public function post($year, $month, $day, $slug)
    {
        $start = Carbon::createFromDate($year, $month, $day)->startOfDay();
        $end   = $start->copy()->endOfDay();

        $post = Post::whereBetween('published_at', [$start, $end])->whereSlug($slug)->first();

        $title = $post->title . ' - Aktuelles';

        return view('news.post', compact('post', 'title'));
    }

    public function guess($slug)
    {
        try {
            $post = Post::published()->whereSlug($slug)->first();

            return redirect($post->url, 301);
        } catch (ModelNotFoundException $e) {
            // TODO: flash error message?

      return redirect()->route('news.index');
        }
    }

    public function comment(NewCommentRequest $request, Guard $guard)
    {
        $post = Post::find($request->input('id'));

        $comment = Comment::create($request->except(['_token', 'id']));

        if ($guard->check()) {
            /* @var \App\Model\User $user */
      $user = $guard->user();
            $user->comments()->save($comment);
        }

        $post->comments()->save($comment);

    // FIXME: akismet complains about an invalid key
    //$this->dispatch(new ValidateComment($comment));

    return redirect()->back()->with('info', 'Der Kommentar wurde erfolgreich gespeichert.');
    }
}
