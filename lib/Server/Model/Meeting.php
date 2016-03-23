<?php

namespace OParl\Server\Model;

class Meeting extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_meetings', 'meeting_id', 'keyword_id');
    }
}
