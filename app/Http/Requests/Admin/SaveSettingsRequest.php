<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SaveSettingsRequest extends Request
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
          'name' => 'string|max:255',
          'email' => 'email',
          'password' => 'string|confirmed|min:6'
        ];
    }
}
