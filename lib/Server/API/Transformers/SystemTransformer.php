<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\System;

class SystemTransformer extends BaseTransformer
{
    public function transform(System $system)
    {
        return [
            'id'                 => route('api.v1.system.show', $system),
            'type'               => 'https://schema.oparl.org/1.0/System',
            'oparlVersion'       => 'https://spec.oparl.org/1.0',
            'name'               => $system->name,
            'body'               => $this->collectionRouteList('api.v1.body.show', $system->bodies),
            'vendor'             => $system->vendor,
            'product'            => $system->product,
            'otherOparlVersions' => [],
            'contactName'        => $system->contact_name,
            'contactEmail'       => $system->contact_email,
            'website'            => $system->website,
            'created'            => $this->formatDate($system->created_at),
            'modified'           => $this->formatDate($system->updated_at),
            'deleted'            => false
        ];
    }
}
