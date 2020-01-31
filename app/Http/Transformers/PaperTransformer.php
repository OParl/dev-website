<?php

namespace App\Http\Transformers;

use App\Model\OParl10Paper;

class PaperTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['location', 'consultation', 'mainFile'];

    public function transform(OParl10Paper $paper)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($paper), [
            'body'                => route('api.oparl.v1.body.show', $paper->body),
            'name'                => $paper->name,
            'reference'           => $paper->reference,
            'date'                => $this->formatDate($paper->date),
            'paperType'           => $paper->paper_type,
            'relatedPaper'        => $this->collectionRouteList('api.oparl.v1.paper.show', $paper->relatedPapers),
            'subordinatedPaper'   => $this->collectionRouteList('api.oparl.v1.paper.show', $paper->subordinatedPapers),
            'superordinatedPaper' => $this->collectionRouteList('api.oparl.v1.paper.show', $paper->superordinatedPapers),
            // mainFile is included
            'auxiliaryFile'          => $this->collectionRouteList('api.oparl.v1.file.show', $paper->auxiliaryFiles),
            'originatorPerson'       => $this->collectionRouteList('api.oparl.v1.person.show', $paper->originatorPeople),
            'underDirectionOf'       => $this->collectionRouteList('api.oparl.v1.organization.show', $paper->underDirectionOfOrganizations),
            'originatorOrganization' => $this->collectionRouteList('api.oparl.v1.organization.show', $paper->originatorOrganizations),
        ]);

        return $this->cleanupData($data, $paper);
    }

    public function includeLocation(OParl10Paper $paper)
    {
        return $this->collection($paper->locations, new LocationTransformer(true), 'included');
    }

    public function includeConsultation(OParl10Paper $paper)
    {
        return $this->collection($paper->consultations, new ConsultationTransformer(true), 'included');
    }

    public function includeMainFile(OParl10Paper $paper)
    {
        return $this->item($paper->mainFile, new FileTransformer(true), 'included');
    }
}
