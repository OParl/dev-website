<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\LegislativeTerm;

class LegislativeTermTransformer extends BaseTransformer
{
    public function transform(LegislativeTerm $legislativeTerm)
    {
        return [
            'id'        => route('api.v1.legislativeterm.show', $legislativeTerm),
            'type'      => 'http://spec.oparl.org/1.0/#entity-legislative-term',
            'body'      => route('api.v1.body.show', $legislativeTerm->body_id),
            'name'      => $legislativeTerm->name,
            'startDate' => $this->formatDate($legislativeTerm->start_date),
            'endDate'   => $this->formatDate($legislativeTerm->end_date),
            'keyword'   => $legislativeTerm->keywords
        ];
    }
}
