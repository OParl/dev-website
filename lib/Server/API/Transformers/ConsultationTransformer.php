<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\Consultation;

class ConsultationTransformer extends BaseTransformer
{
    public function transform(Consultation $consultation)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($consultation), [
            'agendaItem'    => route('api.v1.agendaitem.show', $consultation->agendaItem),
            'meeting'       => route('api.v1.meeting.show', $consultation->meeting),
            'organization'  => ($consultation->organizations) ? $this->collectionRouteList('api.v1.organization.show', $consultation->organizations) : null,
            'authoritative' => (bool) $consultation->authoritative,
            'role'          => $consultation->role,
        ]);

        if (!$this->isIncluded()) {
            $data = array_merge($data, [
                'paper' => route('api.v1.paper.show', $consultation->paper),
            ]);
        }

        return $this->cleanupData($data, $consultation);
    }
}
