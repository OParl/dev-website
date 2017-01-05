<?php

namespace OParl\Server\Model;

class Paper extends BaseModel
{
    protected $dates = ['published_date'];

    public function mainFile()
    {
        return $this->belongsTo(File::class, 'main_file_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_papers', 'paper_id', 'keyword_id');
    }
}
