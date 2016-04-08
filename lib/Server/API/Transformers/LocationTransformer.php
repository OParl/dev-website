<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Location;

class LocationTransformer extends BaseTransformer
{
    public function transform(Location $location)
    {
        return [
            'id' => route('api.v1.location.show', $location),
            'type' => 'http://spec.oparl.org/spezifikation/1.0/#entity-location',
        ];
    }
}
