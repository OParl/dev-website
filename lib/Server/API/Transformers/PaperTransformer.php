<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\Paper;

class PaperTransformer extends BaseTransformer
{
    public function transform(Paper $paper)
    {
        return remove_empty_keys(array_merge($this->getDefaultAttributesForEntity($paper), [
            // TODO: Paper attributes
            'date' => $this->formatDate($paper->date),

        ]));
    }
}
