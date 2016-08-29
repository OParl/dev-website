<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Consultation;

class ConsultationTransformer extends BaseTransformer
{
    public function transform(Consultation $consultation)
    {
        $transformed = [
            'id'         => route('api.v1.consultation.show', $consultation),
            'type'       => 'https://schema.oparl.org/1.0/Consultation',
            'paper'      => ($this->isIncluded()) ? route('api.v1.paper.show', $consultation->paper)  : null,
            'agendaItem' => route('api.v1.agendaitem.show', $consultation->agendaItem),
            'meeting'    => route('api.v1.meeting.show', $consultation->meeting),
            'organization' => $this->collectionRouteList('api.v1.organization.show', $consultation->organizations),
            'authoritative' => (boolean)$consultation->authoritative,
            'role' => $consultation->role,
            'keyword' => $consultation->keywords->pluck('human_name'),
        ];
        return $transformed;
    }
}
