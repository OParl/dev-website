<?php namespace App\Model;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $dates = ['published_at'];
  protected $fillable = ['title', 'slug', 'tags', 'author', 'content', 'published_at'];

  public static function boot()
  {
    static::saved(function ($user) {
      app('cache')->forget('news.archive');
    });
  }

  public function author()
  {
    return $this->belongsTo(User::class, 'author_id', 'id');
  }

  public function tags()
  {
    return $this->belongsToMany(Tag::class);
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  public function getIsPublishedAttribute()
  {
    return !is_null($this->published_at);
  }

  public function getIsScheduledAttribute()
  {
    return !is_null($this->published_at) && $this->published_at > Carbon::now();
  }

  public function getUrlAttribute()
  {
    return route(
      'news.post', [
        $this->published_at->year,
        sprintf('%02d', $this->published_at->month),
        sprintf('%02d', $this->published_at->day),
        $this->slug
      ]
    );
  }

  public function getMarkdownContentAttribute()
  {
    $pd = \Parsedown::instance();

    return $pd->parse($this->content);
  }

  public function getTagListAttribute()
  {
    $list = $this->tags->lists('id');
    return $list->all();
  }

  public function scopePublished($query)
  {
    return $query->whereNotNull('published_at')->where('published_at', '<=', Carbon::now())->orderBy('published_at', 'desc');
  }

  public function scopeDraft($query)
  {
    return $query->whereNull('published_at')->orderBy('created_at');
  }

  public function scopeScheduled($query)
  {
    return $query->whereNotNull('published_at')->where('published_at', '>', Carbon::now())->orderBy('published_at');
  }
}
