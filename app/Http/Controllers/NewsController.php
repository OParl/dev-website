<?php namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Http\Requests;
use App\Model\Post;

class NewsController extends Controller
{
  public function index()
  {
    return view('news.index')->with('posts', Post::published()->paginate(15));
  }

  public function post($year, $month, $day, $slug)
  {
    $date = Carbon::createFromDate($year, $month, $day);

    $post = Post::whereBetween('published_at', [$date->startOfDay(), (new Carbon($date))->endOfDay()])->whereSlug($slug)->first();

    return view('news.post')->with('post', $post);
  }
}
