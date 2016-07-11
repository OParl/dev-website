<?php

namespace OParl\Server\Model;

class Organization extends BaseModel
{
    protected $casts = [
        'external_body' => 'array',
        'post'          => 'array'
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at'
    ];

    public function body()
    {
        return $this->belongsTo(Body::class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::class, 'oparl_memberships', 'organization_id', 'person_id');
    }

    public function members()
    {
        return $this->hasMany(Membership::class, 'organization_id', 'id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_organizations', 'organization_id', 'keyword_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
