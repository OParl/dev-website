<?php

namespace OParl\Server\Model;

class File extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_files', 'file_id', 'keyword_id');
    }
}
