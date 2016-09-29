<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\Consultation;

class ConsultationTransformer extends BaseTransformer
{
    public function transform(Consultation $consultation)
    {
        $transformed = array_merge($this->getDefaultAttributesForEntity($consultation), [
            'paper'         => ($this->isIncluded()) ? route('api.v1.paper.show', $consultation->paper) : null,
            'agendaItem'    => route('api.v1.agendaitem.show', $consultation->agendaItem),
            'meeting'       => route('api.v1.meeting.show', $consultation->meeting),
            'organization'  => $this->collectionRouteList('api.v1.organization.show', $consultation->organizations),
            'authoritative' => (bool) $consultation->authoritative,
            'role'          => $consultation->role,
        ]);

        return $transformed;
    }
}
