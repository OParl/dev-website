<?php

namespace OParl\Server\Model;

class Person extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_people', 'person_id', 'keyword_id');
    }
}
