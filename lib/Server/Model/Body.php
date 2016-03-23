<?php

namespace OParl\Server\Model;

class Body extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_bodies', 'body_id', 'keyword_id');
    }
}
