<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationRequest;
use Illuminate\Http\Request;

class ValidatorController extends Controller
{
    public function validationForm()
    {
        return view('developers.validation.form');
    }

    public function scheduleValidation(ValidationRequest $response)
    {
        // 1) read input data
        $endpoint = $response->input('endpoint');
        $email = $response->input('email');
        $canSaveData = (bool) $response->input('save');

        // 2) schedule validation job
        //$this->dispatch(new ValidatorRunJob($endpoint, $email, $canSaveData));

        // 3) success!
        session()->flash('message', trans('app.validation.success', compact('endpoint')));

        return redirect()->route('validator.schedule.success');
    }

    public function validationScheduleSuccess(Request $request)
    {
//        if (!$request->session()->has('message')) {
//            return redirect()->route('validator.index');
//        }

        $request->session()->put('message', 'Messaage');

        return view('developers.validation.success');
    }

    public function result(Request $request, $endpoint)
    {
        // TODO: show validation results for endpoint if they were stored
        // TODO: show validation progress maybe?
    }
}
