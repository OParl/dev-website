<?php namespace App\Http\Controllers;

class SpecificationController extends Controller {
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('specification.index');
	}
}
