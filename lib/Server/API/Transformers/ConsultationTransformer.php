<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Consultation;

class ConsultationTransformer extends BaseTransformer
{
    public function transform(Consultation $consultation)
    {
        return [
            'id'      => route('api.v1.consultation.show', $consultation),
            'type'    => 'http://spec.oparl.org/spezifikation/1.0/#entity-consultation',
            'keyword' => $consultation->keywords->pluck('human_name'),
        ];
    }
}
