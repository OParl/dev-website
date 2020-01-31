<?php

namespace OParl\Server\Model;

class OParl10Membership extends OParl10BaseModel
{
    protected $table = 'memberships';

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
        return $this->belongsTo(OParl10Person::class);
    }

    public function organization()
    {
        return $this->belongsTo(OParl10Organization::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_memberships', 'membership_id', 'keyword_id');
    }
}
