<?php

namespace OParl\Server\Model;

class Consultation extends BaseModel
{
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function agendaItem()
    {
        return $this->belongsTo(AgendaItem::class);
    }

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_consultations', 'consultation_id', 'keyword_id');
    }
}
