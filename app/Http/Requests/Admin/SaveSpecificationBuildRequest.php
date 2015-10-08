<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SaveSpecificationBuildRequest extends Request
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      // TODO: rules
    ];
  }
}