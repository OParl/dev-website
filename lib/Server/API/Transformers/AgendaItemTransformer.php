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
            'type'    => 'http://spec.oparl.org/spezifikation/1.0/#entity-agendaitem',
            'keyword' => $agendaItem->keywords->pluck('human_name'),
        ];
    }
}
