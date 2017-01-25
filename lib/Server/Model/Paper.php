<?php

namespace OParl\Server\Model;

class Paper extends BaseModel
{
    protected $dates = ['published_date'];

    public function body()
    {
        return $this->belongsTo(Body::class);
    }

    public function mainFile()
    {
        return $this->belongsTo(File::class, 'main_file_id');
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

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_papers', 'paper_id', 'keyword_id');
    }
}
