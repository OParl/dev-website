<?php

namespace App\Model;

/**
 * Class File.
 *
 * @property-read OParl10Meeting|null $meeting
 * @property-read OParl10AgendaItem|null $agendaItem
 * @property-read OParl10Paper|null $paper
 */
class OParl10File extends OParl10BaseModel
{
    protected $table = 'files';
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
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_files', 'file_id', 'keyword_id');
    }

    public function masterFile()
    {
        return $this->belongsTo(self::class, 'master_file_id', 'id');
    }

    public function derivativeFiles()
    {
        return $this->hasMany(self::class, 'id', 'master_file_id');
    }

    // TODO: think about query to marry all the file related meeting attributes to File

    // TODO: agendaItem
    // TODO: paper
}
