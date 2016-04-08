<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Body;
use OParl\Server\Model\Person;

class PersonTransformer extends BaseTransformer
{
    public function transform(Person $person)
    {
        return [
            'id' => route('api.v1.person.show', $person),
            'type' => 'http://spec.oparl.org/spezifikation/1.0/#entity-person',
        ];
    }
}
