<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Organization;

class OrganizationTransformer extends BaseTransformer
{
    public function transform(Organization $organization)
    {
        return [
            'id'   => route('api.v1.organization.show', $organization),
            'type' => 'http://spec.oparl.org/spezifikation/1.0/#entity-organization',
        ];
    }
}
