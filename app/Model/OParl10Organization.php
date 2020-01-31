<?php

namespace App\Model;

class OParl10Organization extends OParl10BaseModel
{
    protected $table = 'organizations';

    protected $casts = [
        'external_body' => 'array',
        'post'          => 'array',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at',
    ];

    public function body()
    {
        return $this->belongsTo(OParl10Body::class);
    }

    public function people()
    {
        return $this->belongsToMany(OParl10Person::class, 'oparl_memberships', 'organization_id', 'person_id');
    }

    public function members()
    {
        // FIXME: This relation is broken
        return $this->hasMany(OParl10Membership::class, 'organization_id', 'id');
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_organizations', 'organization_id', 'keyword_id');
    }

    public function location()
    {
        return $this->belongsTo(OParl10Location::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(OParl10Meeting::class, 'oparl_meetings_organizations');
    }
}
