<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\LiveCopyRepository;

class SpecificationController extends Controller {
	/**
	 * Show the specification's live copy
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(LiveCopyRepository $livecopy)
	{
		return view('specification.index', compact('livecopy'));
	}

  public function image(Filesystem $fs, $image)
  {
    return $fs->get(LiveCopyRepository::IMAGE_PATH.$image);
  }
}
