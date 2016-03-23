<?php

namespace OParl\Server\Model;

class Location extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_locations', 'location_id', 'keyword_id');
    }
}
