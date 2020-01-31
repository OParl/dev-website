<?php

namespace OParl\Server\Model;

class Membership extends OParl10BaseModel
{
    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at',
    ];

    protected $casts = [
        'voting_right' => 'boolean',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_memberships', 'membership_id', 'keyword_id');
    }
}
