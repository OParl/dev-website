<?php namespace App\Http\Requests;

class VersionSelectRequest extends Request
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'version' => 'required|integer|min:0|max:29',
      'email'   => 'sometimes|required|email'
    ];
  }
}
