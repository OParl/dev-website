<?php

namespace OParl\Server\Model;

class Paper extends BaseModel
{
    protected $dates = ['published_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Body
     */
    public function body()
    {
        return $this->belongsTo(Body::class);
    }

    public function mainFile()
    {
        return $this->belongsTo(File::class, 'main_file_id');
    }

    public function auxiliaryFiles()
    {
        return $this->belongsToMany(File::class, 'oparl_papers_auxiliary_files', 'paper_id', 'auxiliary_file_id');
    }

    public function relatedPapers()
    {
        return $this->belongsToMany(Paper::class, 'oparl_papers_related_papers', 'paper_id', 'related_paper_id');
    }

    public function subordinatedPapers()
    {
        return $this->belongsToMany(Paper::class, 'oparl_papers_subordinated_papers', 'paper_id',
            'subordinated_paper_id');
    }

    public function superordinatedPapers()
    {
        return $this->belongsToMany(Paper::class, 'oparl_papers_superordinated_papers', 'paper_id',
            'superordinated_paper_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'oparl_papers_locations', 'paper_id', 'location_id');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function originatorPeople()
    {
        return $this->belongsToMany(Person::class, 'oparl_papers_originator_people', 'paper_id', 'person_id');
    }

    public function originatorOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'oparl_papers_originator_organizations', 'paper_id', 'organization_id');
    }

    public function underDirectionOfOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'oparl_papers_under_direction_of_organizations', 'paper_id', 'organization_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_papers', 'paper_id', 'keyword_id');
    }
}
