<?php

namespace OParl\Server\Model;

/**
 * Class LegislativeTerm.
 */
class LegislativeTerm extends BaseModel
{
    protected $dates = ['start_date', 'end_date'];

    public function body()
    {
        return $this->belongsTo(Body::class, 'body_id', 'id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_legislative_terms', 'legislative_term_id', 'keyword_id');
    }
}
