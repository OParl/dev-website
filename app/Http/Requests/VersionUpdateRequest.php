<?php namespace App\Http\Requests;

class VersionUpdateRequest extends Request
{
  public function authorize()
  {
    return $this->route('key') === env('BUILDKITE_DEPLOY_KEY');
  }

  public function rules()
  {
    return [
      'zip' => 'required',
      'gz'  => 'required',
      'bz'  => 'required',
    ];
  }
}