<?php

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Body;

class BodyTransformer extends BaseTransformer
{
    public function transform(Body $body)
    {
        return [];
    }
}
