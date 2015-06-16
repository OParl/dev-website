<?php namespace App\Http\Controllers;

use OParl\Spec\LiveCopyRepository;

class SpecificationController extends Controller {
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(LiveCopyRepository $livecopy)
	{
		return view('specification.index', compact('livecopy'));
	}
}
