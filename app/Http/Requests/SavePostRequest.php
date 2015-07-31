<?php

namespace App\Http\Requests;

class SavePostRequest extends Request
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
      'id'            => 'sometimes|integer',
      'title'         => 'required|string|min:3|max:255',
      'slug'          => 'required|string|min:3|max:255',
      'published_at'  => 'sometimes|date',
      'content'       => 'required|string|min:10'
    ];
  }
}
