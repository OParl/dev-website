<?php

namespace OParl\Server\Model;

class Organization extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_organizations', 'organization_id', 'keyword_id');
    }
}
