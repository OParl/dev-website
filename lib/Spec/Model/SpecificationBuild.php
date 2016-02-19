<?php namespace OParl\Spec\Model;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Finder\Finder;

class SpecificationBuild extends Model
{
    protected $fillable = ['created_at', 'hash', 'commit_message', 'human_version'];

    protected $casts = ['queried' => 'boolean', 'persistent' => 'boolean', 'displayed' => 'boolean'];

    public static function boot()
    {
        static::creating(function ($build) {
            $build->queried = false;
            $build->persistent = false;
            $build->displayed = true;
        });
    }

    public function getLinkedCommitMessageAttribute()
    {
        $commitMessage = $this->commit_message;

        if (preg_match('/#(\d+)/', $commitMessage, $matches) > 0) {
            $link = sprintf('<a href="//github.com/OParl/spec/issues/%d">%s</a>', $matches[1], $matches[0]);
            $commitMessage = str_replace($matches[0], $link, $commitMessage);
        }

        return $commitMessage;
    }

    public function getShortHashAttribute()
    {
        return substr($this->hash, 0, 7);
    }

    public function getIsAvailableAttribute()
    {
        return is_dir($this->storage_path);
    }

    public function getStoragePathAttribute()
    {
        $path = 'specification/builds/' . $this->hash;

        app('filesystem')->makeDirectory($path);

        return storage_path('app' . DIRECTORY_SEPARATOR . $path);
    }

    public function getExtractedFilesStoragePathAttribute()
    {
        $path = 'specification/builds/' . $this->hash . '/extracted';

        app('filesystem')->makeDirectory($path);

        return storage_path('app' . DIRECTORY_SEPARATOR . $path);
    }

    public function getZipStoragePathAttribute()
    {
        return $this->storage_path . DIRECTORY_SEPARATOR . $this->zip_filename;
    }

    public function getTarGzStoragePathAttribute()
    {
        return $this->storage_path . DIRECTORY_SEPARATOR . $this->tar_gz_filename;
    }

    public function getTarBzStoragePathAttribute()
    {
        return $this->storage_path . DIRECTORY_SEPARATOR . $this->tar_bz_filename;
    }

    public function getZipFilenameAttribute()
    {
        return 'OParl-' . $this->hash . '.zip';
    }

    public function getTarGzFilenameAttribute()
    {
        return 'OParl-' . $this->hash . '.tar.gz';
    }

    public function getTarBzFilenameAttribute()
    {
        return 'OParl-' . $this->hash . '.tar.bz2';
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

    public function discoverExtractedFile($extension)
    {
        $path = $this->extracted_files_storage_path;

        if (!is_dir($path)) {
            return '';
        }

        $finder = Finder::create()->files()->name("*.{$extension}")->in($path);

        return collect(iterator_to_array($finder))->filter(
            function ($file) use ($extension) {
                return ends_with($file, $extension);
            }
        )->first();
    }
}
