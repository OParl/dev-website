<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Http\Controllers;


class EndpointsController
{
    public function index()
    {
        return view(
            'endpoints.index',
            [
                'title' => trans('app.endpoints.title'),
            ]
        );
    }
}
