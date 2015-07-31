<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests\SavePostRequest;
use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;

use Cocur\Slugify\Slugify;

use App\Http\Controllers\Controller;
use App\Model\Post;

class NewsController extends Controller
{
  public function index(Request $request)
  {
    $order = $request->input('order_by', 'created_at');

    // TODO: introduce sorting option
    $sort  = ($order === 'created_at') ? 'desc' : 'asc';

    $posts = Post::with('author')->orderBy($order, $sort)->paginate(15);

    return view('admin.news.index', [
      'posts' => $posts,
      'order' => $order
    ]);
  }

  public function create()
  {
    return view('admin.news.edit', ['post' => (new Post)->newInstance()]);
  }

  public function edit($id)
  {
    return view('admin.news.edit', ['post' => Post::findOrFail($id)]);
  }

  public function slug(Slugify $slugify, Request $request)
  {
    return response()->json(['slug' => $slugify->slugify($request->input('title'))]);
  }

  public function save(SavePostRequest $request, Guard $guard)
  {
    $postData = $request->except(['_token', 'id']);

    if ($postData['published_at'] === '')
    {
      $postData['published_at'] = null;
    } else
    {
      $postData['published_at'] = Carbon::createFromFormat(Carbon::ISO8601, $postData['published_at']);
    }

    if ($request->has('id'))
    {
      $post = Post::find($request->input('id'));
      $post->update($postData);
      $post->save();
    } else
    {
      $post = Post::create($postData);

      /* @var \App\Model\User $user */
      $user = $guard->user();
      $user->posts()->save($post);
    }

    return redirect()->route('admin.news.index')->with('info', 'Der Eintrag “' . $post->title . '” wurde erfolgreich gespeichert!');
  }

  public function delete($id)
  {
    $post = Post::findOrFail($id);

    $title = $post->title;

    $post->delete();

    return redirect()->back()->with('info', 'Succesfully deleted '.$title);
  }
}