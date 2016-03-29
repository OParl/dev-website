<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\AgendaItem;

class AgendaItemTransformer extends BaseTransformer
{
    public function transform(AgendaItem $agendaItem)
    {
        return [];
    }
}
