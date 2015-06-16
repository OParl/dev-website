<?php namespace App\Http\Controllers;

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
}
