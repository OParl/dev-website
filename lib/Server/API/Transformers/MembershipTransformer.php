<?php

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Membership;

class MembershipTransformer extends BaseTransformer
{
    public function transform(Membership $membership)
    {
        return [];
    }
}
