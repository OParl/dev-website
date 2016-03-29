<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Location;

class LocationTransformer extends BaseTransformer
{
    public function transform(Location $location)
    {
        return [];
    }
}
