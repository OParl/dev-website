<?php

namespace OParl\Server\Model;

class Consultation extends BaseModel
{
    public static function boot()
    {
        self::updated(function (Consultation $consultation) {
            if (!is_null($consultation->paper)) {
                $consultation->paper()->body()->associate($consultation->organizations->first()->body);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Paper
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function agendaItem()
    {
        return $this->belongsTo(AgendaItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Meeting
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_consultations', 'consultation_id', 'keyword_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'oparl_consultations_organizations', 'consultation_id', 'organization_id');
    }
}
