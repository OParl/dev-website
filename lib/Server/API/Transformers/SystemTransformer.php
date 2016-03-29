<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\System;

class SystemTransformer extends BaseTransformer
{
    public function transform(System $system)
    {
        return [
            'id'   => strval($system->id),
            'type' => 'http://spec.oparl.org/spezifikation/#system-entity',
        ];
    }
}
