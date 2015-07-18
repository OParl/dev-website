<?php namespace App\Http\Requests;

class VersionSelectRequest extends Request
{
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
      'version' => 'required|string|size:7',
      'email'   => 'required_if:available,0|email',
      'format'  => 'required|in:docx,epub,txt,pdf,odt,html,zip,tar.gz,tar.bz2'
    ];
  }
}
