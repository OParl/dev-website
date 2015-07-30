<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
  public function index()
  {
    return view('admin.settings');
  }
}