<?php

namespace OParl\Server\Model;

/**
 * Class Body.
 */
class OParl10Body extends OParl10BaseModel
{
    protected $table = 'bodies';

    protected $dates = ['license_valid_since', 'oparl_since', 'deleted_at'];

    protected $casts = [
        'equivalent_body' => 'array',
    ];

    public function legislativeTerms()
    {
        return $this->hasMany(OParl10LegislativeTerm::class, 'body_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(OParl10Location::class);
    }

    public function people()
    {
        return $this->hasMany(OParl10Person::class, 'body_id', 'id');
    }

    public function organizations()
    {
        return $this->hasMany(OParl10Organization::class, 'body_id', 'id');
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_bodies', 'body_id', 'keyword_id');
    }

    public function system()
    {
        return $this->belongsTo(OParl10System::class);
    }

    public function papers()
    {
        return $this->hasMany(OParl10Paper::class);
    }
}
