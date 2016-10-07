<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 07/10/2016
 * Time: 18:29
 */

namespace App\Http\Controllers\Hooks;

use App\Http\Requests\Request;

class GitHubHooksController
{
    public function index()
    {
        abort(400);
    }

    public function push(Request $request, $repository)
    {

    }
}