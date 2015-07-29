<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  protected $fillable = ['name', 'slug'];

  public function news()
  {
    return $this->belongsToMany(Post::class);
  }
}
