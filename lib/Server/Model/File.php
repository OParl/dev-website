<?php

namespace OParl\Server\Model;

use Illuminate\Filesystem\Filesystem;

/**
 * Class File
 *
 * @package OParl\Server\Model
 */
class File extends BaseModel
{
    protected $dates = ['date'];

    public static function boot()
    {
        parent::boot();

        // TODO: think about handling of stored files
//        self::deleting(function (File $file, Filesystem $filesystem) {
//            $filesystem->delete($file->storage_filename);
//        });

    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_files', 'file_id', 'keyword_id');
    }

    public function masterFile()
    {
        return $this->belongsTo(File::class, 'master_file_id', 'id');
    }

    public function derivativeFiles()
    {
        return $this->hasMany(File::class, 'id', 'master_file_id');
    }

    // TODO: think about query to marry all the file related meeting attributes to File


    // TODO: agendaItem
    // TODO: paper
}
