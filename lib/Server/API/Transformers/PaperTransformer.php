<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\Paper;

class PaperTransformer extends BaseTransformer
{
    public function transform(Paper $paper)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($paper), [
            'body' => route('api.v1.body.show', $paper->body),
            'name' => $paper->name,
            'reference' => $paper->reference,
            'date' => $this->formatDate($paper->date),
            'paperType' => $paper->paper_type,
            'relatedPaper' => $this->collectionRouteList('api.v1.paper.show', $paper->relatedPapers),
            'subordinatedPaper' => $this->collectionRouteList('api.v1.paper.show', $paper->subordinatedPapers),
            'superordinatedPaper' => $this->collectionRouteList('api.v1.paper.show', $paper->superordinatedPapers),
            'mainFile' => $paper->mainFile,
            'auxiliaryFile' => [], // TODO: auxiliary file
            'location' => $this->collectionRouteList('api.v1.location.show', $paper->locations),
            'originatorPerson' => [], // TODO: originator person
            'underDirectionOf' => [], // TODO: under direction of organization
            'originatorOrganization' => [], // TODO: originator organization
            'consultation' => [], // TODO: consultation
        ]);

        return $this->cleanupData($data, $paper);
    }
}
