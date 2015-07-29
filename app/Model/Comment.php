<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $fillable = ['author_name', 'author_email', 'content'];

  protected $touches = ['post'];

  public function post()
  {
    return $this->belongsTo(Post::class);
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
}
