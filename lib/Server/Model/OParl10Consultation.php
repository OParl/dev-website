<?php

namespace OParl\Server\Model;

class OParl10Consultation extends OParl10BaseModel
{
    protected $table = 'consultations';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|OParl10Paper
     */
    public function paper()
    {
        return $this->belongsTo(OParl10Paper::class);
    }

    public function agendaItem()
    {
        return $this->belongsTo(OParl10AgendaItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|OParl10Meeting
     */
    public function meeting()
    {
        return $this->belongsTo(OParl10Meeting::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_consultations', 'consultation_id', 'keyword_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(OParl10Organization::class, 'oparl_consultations_organizations', 'consultation_id', 'organization_id');
    }
}
