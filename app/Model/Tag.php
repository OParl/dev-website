<?php namespace App\Model;

use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public static function boot()
    {
        static::creating(function (Tag $tag) {
      $slugify = Slugify::create();

      $tag->slug = $slugify->slugify($tag->name);
    });
    }

    public function news()
    {
        return $this->belongsToMany(Post::class);
    }
}
