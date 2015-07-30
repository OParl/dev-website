<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class NewsController extends Controller
{
  public function index()
  {
    return view('admin.news.index');
  }

  public function create()
  {
    return view('admin.news.create');
  }
}