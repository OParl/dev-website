<?php

namespace OParl\Server\Model;

use Illuminate\Filesystem\Filesystem;

class File extends BaseModel
{
    public static function boot()
    {
        self::deleting(function (File $file, Filesystem $filesystem) {
            $filesystem->delete($file->storage_filename);
        });
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_files', 'file_id', 'keyword_id');
    }
}
