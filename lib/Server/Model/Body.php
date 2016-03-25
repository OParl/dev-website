<?php

namespace OParl\Server\Model;

class Body extends BaseModel
{
    public function legislativeTerms()
    {
        return $this->hasMany(LegislativeTerm::class, 'body_id', 'id');
    }

    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_bodies', 'body_id', 'keyword_id');
    }
}
