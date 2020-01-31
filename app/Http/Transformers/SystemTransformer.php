<?php

namespace App\Http\Transformers;

use App\Model\OParl10System;

class SystemTransformer extends BaseTransformer
{
    public function transform(OParl10System $system)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($system), [
            'oparlVersion'       => 'https://schema.oparl.org/1.0/',
            'name'               => $system->name,
            'body'               => route_where('api.oparl.v1.body.index', ['system' => $system->id]),
            'vendor'             => $system->vendor,
            'product'            => $system->product,
            'otherOparlVersions' => [],
            'contactName'        => $system->contact_name,
            'contactEmail'       => $system->contact_email,
            'license'            => 'https://creativecommons.org/licenses/by-sa/4.0/',
            'website'            => $system->website,
            'deleted'            => false,
        ]);

        return $this->cleanupData($data, $system);
    }
}
