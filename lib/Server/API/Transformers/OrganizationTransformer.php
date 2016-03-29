<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Organization;

class OrganizationTransformer extends BaseTransformer
{
    public function transform(Organization $organization)
    {
        return [];
    }
}
