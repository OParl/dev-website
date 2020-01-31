<?php

namespace OParl\Server\Model;

class Location extends OParl10BaseModel
{
    public function bodies()
    {
        return $this->hasMany(Body::class);
    }

    public function people()
    {
        return $this->hasMany(Person::class);
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_locations', 'location_id', 'keyword_id');
    }
}
