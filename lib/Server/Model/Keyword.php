<?php

namespace OParl\Server\Model;

use Cocur\Slugify\Slugify;

class Keyword extends BaseModel
{
    public function getNameAttribute()
    {
        if (is_null($this->attributes['name'])) {
            $slugify = Slugify::create();
            $this->attributes['name'] = $slugify->slugify($this->human_name);
            $this->save();
        }

        return $this->attributes['name'];
    }

    public function agendaItems()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_agenda_items', 'keyword_id', 'agenda_item_id');
    }

    public function bodies()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_bodies', 'keyword_id', 'body_id');
    }

    public function consultations()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_consultations', 'keyword_id', 'consultation_id');
    }

    public function files()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_files', 'keyword_id', 'file_id');
    }

    public function legislativeTerms()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_legislative_terms', 'keyword_id',
            'legislative_term_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_locations', 'keyword_id', 'location_id');
    }

    public function meetings()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_meetings', 'keyword_id', 'meeting_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_organizations', 'keyword_id', 'organization_id');
    }

    public function papers()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_papers', 'keyword_id', 'paper_id');
    }

    public function people()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_people', 'keyword_id', 'person_id');
    }
}
