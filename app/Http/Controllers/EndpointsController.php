<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Http\Controllers;


use App\Model\Endpoint;
use Illuminate\Contracts\Filesystem\Filesystem;

class EndpointsController
{
    public function index(Filesystem $fs)
    {
        $endpoints = Endpoint::all();

        return view('developers.endpoints', [
            'title'     => trans('app.endpoints.title'),
            'endpoints' => $endpoints,
        ]);
    }
}
