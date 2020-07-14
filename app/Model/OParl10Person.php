<?php

namespace App\Model;

/**
 * Class OParl10Person
 *
 * @property OParl10Location|null $location
 * @property string $status
 */
class OParl10Person extends OParl10BaseModel
{
    protected $table = 'people';

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
        return $this->hasMany(OParl10Membership::class);
    }

    public function location()
    {
        return $this->belongsTo(OParl10Location::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_people', 'person_id', 'keyword_id');
    }
}
