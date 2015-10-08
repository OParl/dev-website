<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveSettingsRequest;
use Illuminate\Auth\Guard;

class SettingsController extends Controller
{
  public function index()
  {
    return view('admin.settings');
  }

  public function save(SaveSettingsRequest $request, Guard $guard)
  {
    /* @var \App\Model\User $user */
    $user = $guard->user();

    $input = $request->except(['password_confirmation', '_token']);
    $input['password'] = bcrypt($input['password']);

    $user->update($input);

    return redirect()->back()->with('info', 'Die Einstellungen wurden gespeichert!');
  }
}