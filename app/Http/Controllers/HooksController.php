<?php namespace App\Http\Controllers;

class HooksController extends Controller
{
  public function updateVersions()
  {
    // GitHub update hook, load new version hashes
  }

  public function addVersion()
  {
    // Buildkite build finish hook, receive a packed fully built version
  }
}