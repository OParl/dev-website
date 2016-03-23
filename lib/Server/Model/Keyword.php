<?php

namespace OParl\Server\Model;

use Cocur\Slugify\Slugify;

class Keyword extends BaseModel
{
    public function getNameAttribute() {
        if (is_null($this->attributes['name'])) {
            $slugify = Slugify::create();
            $this->attributes['name'] = $slugify->slugify($this->human_name);
        }
    }
}
