<?php

namespace App\Http\Transformers;

use App\Model\OParl10AgendaItem;

class AgendaItemTransformer extends BaseTransformer
{
    public function transform(OParl10AgendaItem $agendaItem)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($agendaItem), [
            'meeting'        => route('api.oparl.v1.meeting.show', $agendaItem->meeting),
            'number'         => $agendaItem->number,
            'name'           => $agendaItem->name,
            'public'         => (bool) $agendaItem->public,
            'consultation'   => route('api.oparl.v1.consultation.show', $agendaItem->consultation),
            'result'         => $agendaItem->result,
            'resolutionText' => $agendaItem->resolutionText,

            // resolutionFile is included
            // auxiliaryFile is included

            'start' => $this->formatDateTime($agendaItem->updated_at),
            'end'   => $this->formatDateTime($agendaItem->updated_at),
        ]);

        return $this->cleanupData($data, $agendaItem);
    }
}
