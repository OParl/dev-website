<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SaveSpecificationBuildRequest extends Request
{
  protected $checkboxes = ['persistent', 'displayed'];

  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'id' => 'required|exists:specification_builds',
      'human_version' => 'required|string|min:5',
      'persistent' => 'required',
      'displayed' => 'required'
    ];
  }
}