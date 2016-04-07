<?php

namespace OParl\Server\Model;

class Body extends BaseModel
{
    protected $dates = ['license_valid_since', 'oparl_since'];
    
    protected $casts = [
        'equivalent_body' => 'array'
    ];

    public function legislativeTerms()
    {
        return $this->hasMany(LegislativeTerm::class, 'body_id', 'id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_bodies', 'body_id', 'keyword_id');
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }
}
