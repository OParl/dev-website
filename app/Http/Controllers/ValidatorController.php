<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\ValidationRequest;
use OParl\Spec\Jobs\ValidatorRunJob;

class ValidatorController extends Controller
{
    public function scheduleValidation(ValidationRequest $response)
    {
        // 1) read input data
        $endpoint = $response->input('endpoint');
        $email = $response->input('email');
        $canSaveData = (bool)$response->input('save');

        // 2) schedule validation job
        $this->dispatch(new ValidatorRunJob($endpoint, $email, $canSaveData));

        // 3) success!
        return redirect()->route('developers.index')->with('message',
            trans('app.validation.success', compact('endpoint')));
    }

    public function result(Request $request, $endpoint)
    {
        // TODO: show validation results for endpoint if they were stored
        // TODO: show validation progress maybe?
    }
}
