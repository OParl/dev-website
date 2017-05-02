<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Http\Requests\ValidationRequest;
use GuzzleHttp\Client;
use OParl\Spec\Jobs\ValidatorRunJob;

class ValidatorController extends Controller
{
    public function scheduleValidation(ValidationRequest $response)
    {
        // 1) test if we got an actual endpoint
        $endpoint = $response->input('endpoint');
        $email = $response->input('email');
        $canSaveData = $response->input('save');

        try {
            $client = new Client([
                'timeout' => 10
            ]);

            $response = $client->request('get', $endpoint);

            if ($response->getStatusCode() !== 200) {
                return redirect()->route('developers.index')->with('message',
                    trans('app.validation.invalid_url', compact('endpoint')));
            }

            $json = json_decode($response->getBody(), true);

            if (!isset($json['name'])) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            return redirect()->route('developers.index')->with('message',
                trans('app.validation.invalid_url', compact('endpoint')));
        }

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
