<?php

namespace OParl\Server\Model;

class Paper extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_papers', 'paper_id', 'keyword_id');
    }
}
