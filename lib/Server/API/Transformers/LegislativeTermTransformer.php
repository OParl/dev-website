<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\LegislativeTerm;

class LegislativeTermTransformer extends BaseTransformer
{
    public function transform(LegislativeTerm $legislativeTerm)
    {
        $data = [
            'id'        => route('api.v1.legislativeterm.show', $legislativeTerm),
            'type'      => 'https://schema.oparl.org/1.0/LegislativeTerm',
            'name'      => $legislativeTerm->name,
            'startDate' => $this->formatDate($legislativeTerm->start_date),
            'endDate'   => $this->formatDate($legislativeTerm->end_date),
            'keyword'   => $legislativeTerm->keywords->pluck('human_name'),
            'created'   => $this->formatDate($legislativeTerm->created_at),
            'modified'  => $this->formatDate($legislativeTerm->updated_at),
            'deleted'   => $legislativeTerm->trashed(),
        ];

        if (!$this->isIncluding()) {
            $data['body'] = route('api.v1.body.show', $legislativeTerm->body_id);
        }

        return $data;
    }
}
