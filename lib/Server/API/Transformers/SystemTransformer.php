<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\System;

class SystemTransformer extends BaseTransformer
{
    public function transform(System $system)
    {
        return array_merge($this->getDefaultAttributesForEntity($system), [
            'oparlVersion'       => 'https://spec.oparl.org/1.0',
            'name'               => $system->name,
            'body'               => $this->collectionRouteList('api.v1.body.show', $system->bodies),
            'vendor'             => $system->vendor,
            'product'            => $system->product,
            'otherOparlVersions' => [],
            'contactName'        => $system->contact_name,
            'contactEmail'       => $system->contact_email,
            'website'            => $system->website,
            'deleted'            => false,
        ]);
    }
}
