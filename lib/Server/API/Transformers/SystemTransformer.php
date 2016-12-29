<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\System;

class SystemTransformer extends BaseTransformer
{
    public function transform(System $system)
    {
        return remove_empty_keys(array_merge($this->getDefaultAttributesForEntity($system), [
            'oparlVersion'       => 'https://spec.oparl.org/1.0',
            'name'               => $system->name,
            'body'               => route_where('api.v1.body.index', ['system' => $system->id]),
            'vendor'             => $system->vendor,
            'product'            => $system->product,
            'otherOparlVersions' => [],
            'contactName'        => $system->contact_name,
            'contactEmail'       => $system->contact_email,
            'website'            => $system->website,
            'deleted'            => false,
        ]));
    }
}
