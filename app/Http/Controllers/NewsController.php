<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Model\Post;

class NewsController extends Controller
{
    public function index()
    {
      return view('news.index')->with('posts', Post::published()->paginate(15));
    }
}
