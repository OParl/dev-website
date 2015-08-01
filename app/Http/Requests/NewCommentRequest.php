<?php namespace App\Http\Requests;

class NewCommentRequest extends Request
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
      'id'      => 'required_without_all:name,email|integer',
      'name'    => 'required_without:id|string|min:2',
      'email'   => 'required_without:id|email',
      'content' => 'required|string|min:3'
    ];
  }
}
