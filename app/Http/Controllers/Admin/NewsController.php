<?php namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;

use Cocur\Slugify\Slugify;

use App\Http\Requests\SavePostRequest;
use App\Http\Controllers\Controller;

use App\Model\Post;
use App\Model\Tag;

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
    return $this->getEditView();
  }

  public function edit($id)
  {
    return $this->getEditView(Post::findOrFail($id));
  }

  public function slug(Slugify $slugify, Request $request)
  {
    return response()->json(['slug' => $slugify->slugify($request->input('title'))]);
  }

  public function save(SavePostRequest $request, Guard $guard)
  {
    $postData = $request->except(['_token', 'id', 'tags']);

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
    } else
    {
      $post = Post::create($postData);

      /* @var \App\Model\User $user */
      $user = $guard->user();
      $user->posts()->save($post);
    }

    $tags = collect($request->input('tags'))->map(function ($tag) {
      if (is_numeric($tag))
      {
        return $tag;
      } else
      {
        $newTag = Tag::create(['name' => $tag]);
        return $newTag->id;
      }
    });

    $post->tags()->sync($tags->all());

    $post->save();

    return redirect()->route('admin.news.index')->with('info', 'Der Eintrag “' . $post->title . '” wurde erfolgreich gespeichert!');
  }

  public function delete($id)
  {
    $post = Post::findOrFail($id);

    $title = $post->title;

    $post->delete();

    return redirect()->back()->with('info', 'Succesfully deleted '.$title);
  }

  /**
   * @return \Illuminate\View\View
   **/
  protected function getEditView($post = null)
  {
    if (is_null($post)) $post = (new Post)->newInstance();

    $tags = Tag::all()->lists('name', 'id');

    return view('admin.news.edit', compact('post', 'tags'));
  }
}