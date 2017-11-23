<?php

namespace App\Http\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|email',
            'endpoint' => 'required|url',
            'save'     => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => trans('app.validation.form.email.required'),
            'email.email'       => trans('app.validation.form.email.invalid'),
            'endpoint.required' => trans('app.validation.form.endpoint.required'),
            'endpoint.url'      => trans('app.validation.form.endpoint.invalid'),
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            // Check wether we got an actual OParl Endpoint
            $client = new Client([
                'timeout' => 10,
            ]);

            try {
                $endpoint = $this->request->get('endpoint');

                $response = $client->request('get', $endpoint);

                if ($response->getStatusCode() !== 200) {
                    $validator->errors()->add('endpoint', trans('app.validation.form.endpoint.no_oparl', compact('endpoint')));
                }

                $json = @json_decode($response->getBody(), true);

                if (!isset($json['name'])) {
                    $validator->errors()->add('endpoint', trans('app.validation.form.endpoint.no_oparl', compact('endpoint')));
                }
            } catch (RequestException $e) {
                $validator->errors()->add('endpoint', trans('app.validation.form.endpoint.no_oparl', compact('endpoint')));
            }
        });
    }
}
