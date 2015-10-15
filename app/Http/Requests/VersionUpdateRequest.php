<?php namespace App\Http\Requests;

class VersionUpdateRequest extends Request
{
    public function authorize()
    {
        return $this->input('key') === env('BUILDKITE_DEPLOY_KEY');
    }

    public function rules()
    {
        return [
      'version' => 'required|string|min:4|max:32'
    ];
    }
}
