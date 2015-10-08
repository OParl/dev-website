<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest {

	protected $checkboxes = [];

  /**
   * Checkbox fixing curtesy of https://laracasts.com/discuss/channels/general-discussion/laravel-5-validation-input-format/replies/85007
   *
   * @param null $key
   * @param null $default
   * @return array|string
   */
  public function input($key = null, $default = null){

    // If looking for a specific key, return it using default method
    if(!empty($key))
      return parent::input($key, $default);

    // Otherwise, let's fix our checkboxes
    $input = parent::input();

    if (is_array($this->checkboxes))
    {
      foreach ($this->checkboxes as $box)
      {
        $input[$box] = isset($input[$box]) ? true : false;
      }
    }

    return $input;
  }

}
