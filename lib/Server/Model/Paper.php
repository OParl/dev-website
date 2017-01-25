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

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'oparl_keywords_papers', 'paper_id', 'keyword_id');
    }

    public function releatedPapers()
    {
        return $this->belongsToMany(Paper::class, 'oparl_papers_related_papers', 'paper_id', 'related_paper_id');
    }
}
