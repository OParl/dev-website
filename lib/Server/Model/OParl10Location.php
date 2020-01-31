<?php

namespace OParl\Server\Model;

class OParl10Location extends OParl10BaseModel
{
    protected $table = 'locations';

    public function bodies()
    {
        return $this->hasMany(OParl10Body::class);
    }

    public function people()
    {
        return $this->hasMany(OParl10Person::class);
    }

    public function organizations()
    {
        return $this->hasMany(OParl10Organization::class);
    }

    public function meetings()
    {
        return $this->hasMany(OParl10Meeting::class);
    }

    public function papers()
    {
        return $this->hasMany(OParl10Paper::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_locations', 'location_id', 'keyword_id');
    }
}
