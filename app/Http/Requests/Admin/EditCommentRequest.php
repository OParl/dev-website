<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class EditCommentRequest extends Request
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
      'id' => 'required|exists:comments',
      'author_id' => 'required_without_all:author_email,author_name|exists:users,id',
      'author_email' => 'required_without:author_id|email',
      'author_name' => 'required_without:author_id|string|min:2',
      'content' => 'required|string|min:3'
    ];
  }
}
