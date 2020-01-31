<?php

namespace OParl\Server\Model;

class OParl10AgendaItem extends OParl10BaseModel
{
    protected $table = 'agenda_items';

    public function meeting()
    {
        return $this->belongsTo(OParl10Meeting::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_agenda_items', 'agenda_item_id', 'keyword_id');
    }

    public function consultation()
    {
        return $this->hasOne(OParl10Consultation::class);
    }
}
