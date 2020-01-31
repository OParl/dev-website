<?php

namespace App\Http\Controllers\OParl\V10;

use App\Http\Controllers\Controller;

class RootController extends Controller
{
    public function index()
    {
        return redirect()->route('api.oparl.v1.system.index', ['format' => 'html']);
    }
}
