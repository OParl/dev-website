<?php

namespace OParl\Server\Model;

class Person extends OParl10BaseModel
{
    protected $casts = [
        'title' => 'array',
        'phone' => 'array',
        'email' => 'array',
    ];

    public function getNameAttribute()
    {
        $title = implode(' ', $this->title);

        return implode(' ', [$this->form_of_address, $title, $this->affix, $this->given_name, $this->family_name]);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_people', 'person_id', 'keyword_id');
    }
}
