<?php

namespace OParl\Server\Model;

class Consultation extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_consultations', 'consultation_id', 'keyword_id');
    }
}
