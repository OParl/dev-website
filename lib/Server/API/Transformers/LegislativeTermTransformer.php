<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\LegislativeTerm;

class LegislativeTermTransformer extends BaseTransformer
{
    public function transform(LegislativeTerm $legislativeTerm)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($legislativeTerm), [
            'name'      => $legislativeTerm->name,
            'startDate' => $this->formatDate($legislativeTerm->start_date),
            'endDate'   => $this->formatDate($legislativeTerm->end_date),
        ]);

        if (!$this->isIncluded()) {
            $data['body'] = route('api.v1.body.show', $legislativeTerm->body_id);
        }

        return remove_empty_keys($data);
    }
}
