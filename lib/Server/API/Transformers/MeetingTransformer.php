<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Meeting;

class MeetingTransformer extends BaseTransformer
{
    public function transform(Meeting $meeting)
    {
        return [];
    }
}
