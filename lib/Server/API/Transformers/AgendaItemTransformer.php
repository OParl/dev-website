<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\AgendaItem;

class AgendaItemTransformer extends BaseTransformer
{
    public function transform(AgendaItem $agendaItem)
    {
        return [
            'id'      => route('api.v1.agendaitem.show', $agendaItem->id),
            'type'    => 'https://schema.oparl.org/1.0/AgendaItem',
            'keyword' => $agendaItem->keywords->pluck('human_name'),
        ];
    }
}
