<?php

namespace OParl\Server\Model;

/**
 * Class LegislativeTerm.
 */
class OParl10LegislativeTerm extends OParl10BaseModel
{
    protected $table = 'legislative_terms';

    protected $dates = ['start_date', 'end_date'];

    public function body()
    {
        return $this->belongsTo(OParl10Body::class, 'body_id', 'id');
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_legislative_terms', 'legislative_term_id', 'keyword_id');
    }
}
