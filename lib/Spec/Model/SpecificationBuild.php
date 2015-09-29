<?php namespace OParl\Spec\Model;

use Illuminate\Database\Eloquent\Model;

class SpecificationBuild extends Model
{
  protected $fillable = ['created_at', 'hash', 'commit_message'];

  public static function boot()
  {
    static::creating(function () {
      // TODO: check if the hash is already available
    });
  }

  public function getLinkedCommitMessageAttribute()
  {
    $commitMessage = $this->commitMessage;

    if (preg_match('/#(\d+)/', $commitMessage, $matches) > 0)
    {
      $link = sprintf('<a href="//github.com/OParl/spec/issues/%d">%s</a>', $matches[1], $matches[0]);
      $commitMessage = str_replace($matches[0], $link, $commitMessage);
    }

    return $commitMessage;
  }

  public function getIsAvailableAttribute()
  {
    return is_dir($this->storage_path);
  }

  public function getStoragePathAttribute()
  {
    $path = 'specification/builds/' . $this->hash;

    app('filesystem')->makeDirectory($path);

    return storage_path('app' . $path);
  }

  public function getExtractedFilesStoragePathAttribute()
  {
    $path = 'specification/builds/' . $this->hash . '/extracted';

    app('filesystem')->makeDirectory($path);

    return storage_path('app' . $path);
  }

  public function getZipArchiveStoragePathAttribute()
  {
    return $this->storage_path . '/OParl-' . $this->hash . '.zip';
  }

  public function getTarGzArchiveStoragePathAttribute()
  {
    return $this->storage_path . '/OParl-' . $this->hash . '.tar.gz';
  }

  public function getTarBzArchiveStoragePathAttribute()
  {
    return $this->storage_path . '/OParl-' . $this->hash . '.tar.bz2';
  }

  public function enqueue()
  {
    $this->queried = true;
    $this->save();
  }

  public function dequeue()
  {
    $this->queried = false;
    $this->save();
  }
}
