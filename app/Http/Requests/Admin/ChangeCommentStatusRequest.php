<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ChangeCommentStatusRequest extends Request
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
      'id' => 'exists:comments',
      'status' => 'in:ham,spam,unvalidated'
    ];
  }
}
