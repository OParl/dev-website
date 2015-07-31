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

  public function yearly($year)
  {
    $start = Carbon::createFromDate($year)->startOfYear();
    $end   = (new Carbon($start))->endOfYear();

    $posts = Post::published()->whereBetween('published_at', [$start, $end])->orderBy('published_at')->paginate(15);

    return view('news.index')->with('posts', $posts);
  }

  public function monthly($year, $month)
  {
    $start = Carbon::createFromDate($year, $month)->startOfMonth();
    $end   = (new Carbon($start))->endOfMonth();

    $posts = Post::published()->whereBetween('published_at', [$start, $end])->orderBy('published_at')->paginate(15);

    return view('news.index')->with('posts', $posts);
  }

  public function daily($year, $month, $day)
  {
    $start = Carbon::createFromDate($year, $month, $day)->startOfDay();
    $end   = (new Carbon($start))->endOfDay();

    $posts = Post::published()->whereBetween('published_at', [$start, $end])->orderBy('published_at')->paginate(15);

    return view('news.index')->with('posts', $posts);
  }

  public function post($year, $month, $day, $slug)
  {
    $date = Carbon::createFromDate($year, $month, $day);

    $post = Post::whereBetween('published_at', [$date->startOfDay(), (new Carbon($date))->endOfDay()])->whereSlug($slug)->first();

    return view('news.post')->with('post', $post);
  }
}
