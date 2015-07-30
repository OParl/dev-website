<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Post;

class NewsController extends Controller
{
  public function index()
  {
    return view('admin.news.index')->with('posts', Post::all());
  }

  public function create()
  {
    return view('admin.news.edit', ['post' => (new Post)->newInstance()]);
  }

  public function edit($id)
  {
    return view('admin.news.edit', ['post' => Post::findOrFail($id)]);
  }

  public function save()
  {
    return redirect()->back()->withInput();
  }

  public function delete($id)
  {
    $post = Post::findOrFail($id);

    $title = $post->title;

    // TODO: delete

    return redirect()->back()->with('info', 'Succesfully deleted '.$title);
  }
}