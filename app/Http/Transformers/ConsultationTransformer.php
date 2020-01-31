<?php

namespace App\Http\Transformers;

use App\Model\OParl10Consultation;

class ConsultationTransformer extends BaseTransformer
{
    public function transform(OParl10Consultation $consultation)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($consultation), [
            'agendaItem'    => route('api.oparl.v1.agendaitem.show', $consultation->agendaItem),
            'meeting'       => route('api.oparl.v1.meeting.show', $consultation->meeting),
            'organization'  => ($consultation->organizations) ? $this->collectionRouteList('api.oparl.v1.organization.show', $consultation->organizations) : null,
            'authoritative' => (bool) $consultation->authoritative,
            'role'          => $consultation->role,
        ]);

        if (!$this->isIncluded()) {
            $data = array_merge($data, [
                'paper' => route('api.oparl.v1.paper.show', $consultation->paper),
            ]);
        }

        return $this->cleanupData($data, $consultation);
    }
}
