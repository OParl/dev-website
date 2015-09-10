<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
  protected $table = 'environment_variables';

  protected $casts = ['value' => 'array'];

  public static function get($key)
  {
    return static::whereKey($key)->first();
  }

  public static function set($key, array $value)
  {
    /* @var Environment $model */
    $model = static::whereKey($key);
    $model->value = $value;

    return $model->save();
  }
}
