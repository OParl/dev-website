<?php

namespace App\Model;

class OParl10Paper extends OParl10BaseModel
{
    protected $table = 'papers';

    protected $dates = ['published_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|OParl10Body
     */
    public function body()
    {
        return $this->belongsTo(OParl10Body::class);
    }

    public function mainFile()
    {
        return $this->belongsTo(OParl10File::class, 'main_file_id');
    }

    public function auxiliaryFiles()
    {
        return $this->belongsToMany(OParl10File::class, 'oparl_papers_auxiliary_files', 'paper_id', 'auxiliary_file_id');
    }

    public function relatedPapers()
    {
        return $this->belongsToMany(self::class, 'oparl_papers_related_papers', 'paper_id', 'related_paper_id');
    }

    public function subordinatedPapers()
    {
        return $this->belongsToMany(self::class, 'oparl_papers_subordinated_papers', 'paper_id',
            'subordinated_paper_id');
    }

    public function superordinatedPapers()
    {
        return $this->belongsToMany(self::class, 'oparl_papers_superordinated_papers', 'paper_id',
            'superordinated_paper_id');
    }

    public function locations()
    {
        return $this->belongsToMany(OParl10Location::class, 'oparl_papers_locations', 'paper_id', 'location_id');
    }

    public function consultations()
    {
        return $this->hasMany(OParl10Consultation::class, 'paper_id', 'id');
    }

    public function originatorPeople()
    {
        return $this->belongsToMany(OParl10Person::class, 'oparl_papers_originator_people', 'paper_id', 'person_id');
    }

    public function originatorOrganizations()
    {
        return $this->belongsToMany(OParl10Organization::class, 'oparl_papers_originator_organizations', 'paper_id', 'organization_id');
    }

    public function underDirectionOfOrganizations()
    {
        return $this->belongsToMany(OParl10Organization::class, 'oparl_papers_under_direction_of_organizations', 'paper_id', 'organization_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(OParl10Keyword::class, 'oparl_keywords_papers', 'paper_id', 'keyword_id');
    }
}
