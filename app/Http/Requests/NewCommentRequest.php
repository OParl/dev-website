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
      'id'         => 'required|integer|exists:comments',
      'has_author' => 'required_without_all:name,email|matches:true',
      'name'       => 'required_without:has_author|string|min:2',
      'email'      => 'required_without:has_author|email',
      'content'    => 'required|string|min:3'
    ];
  }
}
