<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\AgendaItem;

class AgendaItemTransformer extends BaseTransformer
{
    public function transform(AgendaItem $agendaItem)
    {
        return [
            'id'             => route('api.v1.agendaitem.show', $agendaItem->id),
            'type'           => 'https://schema.oparl.org/1.0/AgendaItem',
            'meeting'        => route('api.v1.meeting.show', $agendaItem->meeting),
            'number'         => $agendaItem->number,
            'name'           => $agendaItem->name,
            'public'         => (boolean)$agendaItem->public,
            'consultation'   => route('api.v1.consultation.show', $agendaItem->consultation),
            'result'         => $agendaItem->result,
            'resolutionText' => $agendaItem->resolutionText,

            // resolutionFile is included
            // auxiliaryFile is included

            'start' => $this->formatDate($agendaItem->updated_at),
            'end'   => $this->formatDate($agendaItem->updated_at),

            'keyword' => $agendaItem->keywords->pluck('human_name'),
            'web'     => 'http://example.org', // TODO: fix me
        ];
    }
}
