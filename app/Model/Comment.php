<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $fillable = ['author_name', 'author_email', 'content'];

  protected $touches = ['post'];

  public static function boot()
  {
    static::creating(function (Comment $comment) {
      if (\Auth::check())
      {
        $comment->status = 'ham';
      } else
      {
        $comment->status = 'unvalidated';
      }
    });
  }

  public function post()
  {
    return $this->belongsTo(Post::class, 'post_id');
  }

  public function author()
  {
    return $this->hasOne(User::class, 'id', 'author_id');
  }

  public function getAuthorEMailAttribute()
  {
    if (!is_null($this->author))
    {
      return $this->author->email;
    } else
    {
      return $this->attributes['author_email'];
    }
  }

  public function getAuthorNameAttribute()
  {
    if (!is_null($this->author))
    {
      return $this->author->name;
    } else
    {
      return $this->attributes['author_name'];
    }
  }

  public function getGravatarAttribute()
  {
    /**
     * - only return avatars rated g or pg
     * - use retro themed default otherwise
     **/
    $baseURL = "//gravatar.com/avatar/%s&r=pg&d=retro&s=32";

    /**
     * - trim whitespace
     * - make lower case
     * - compute md5 hash
     **/
    $hash = md5(strtolower(trim($this->author_email)));

    return sprintf($baseURL, $hash);
  }

  public function getMarkdownContentAttribute()
  {
    $pd = \Parsedown::instance();
    return $pd->parse($this->content);
  }

  public function scopeHam($query)
  {
    return $query->whereStatus('ham');
  }

  public function scopeSpam($query)
  {
    return $query->whereStatus('spam');
  }

  public function scopeUnvalidated($query)
  {
    return $query->whereStatus('unvalidated');
  }
}
