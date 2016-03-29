<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Consultation;

class ConsultationTransformer extends BaseTransformer
{
    public function transform(Consultation $consultation)
    {
        return [];
    }
}
