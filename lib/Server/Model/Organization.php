<?php

namespace OParl\Server\Model;

class Organization extends BaseModel
{
    protected $dates = ['start_date', 'end_date'];

    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_organizations', 'organization_id', 'keyword_id');
    }

    public function location() {
        return $this->hasOne(Location::class);
    }
}
