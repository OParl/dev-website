<?php

namespace OParl\Server\Model;

class AgendaItem extends BaseModel
{
    public function keywords() {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_agenda_items', 'agenda_item_id', 'keyword_id');
    }
}
