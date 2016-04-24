<?php

namespace OParl\Server\Model;

class Meeting extends BaseModel
{
    protected $casts = [
        'cancelled' => 'boolean',
    ];

    protected $dates = [
        'deleted_at',
        'start',
        'end',
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'oparl_meetings_organizations', 'meeting_id',
            'organization_id');
    }
    public function participants()
    {
        return $this->belongsToMany(Person::class, 'oparl_meetings_participants', 'meeting_id', 'participant_id');
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function invitation()
    {
        return $this->belongsTo(File::class, 'invitation_id');
    }

    public function resultsProtocol()
    {
        return $this->belongsTo(File::class, 'results_protocol_id');
    }

    public function verbatimProtocol()
    {
        return $this->belongsTo(File::class, 'verbatim_protocol_id');
    }

    public function auxiliaryFiles()
    {
        return $this->belongsToMany(File::class, 'oparl_meetings_auxiliary_files', 'meeting_id', 'file_id');
    }

    public function agendaItems()
    {
        return $this->hasMany(AgendaItem::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_meetings', 'meeting_id', 'keyword_id');
    }
}
