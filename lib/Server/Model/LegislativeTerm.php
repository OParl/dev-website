<?php

namespace OParl\Server\Model;

class LegislativeTerm extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_legislative_terms', 'legislative_term_id', 'keyword_id');
    }
}
