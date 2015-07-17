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
      'zip' => 'required|mimetypes:application/zip,application/octet-stream',
      'gz'  => 'required|mimetypes:application/gzip,application/octet-stream',
      'bz'  => 'required|mimetypes:application/bzip,application/octet-stream',
    ];
  }
}