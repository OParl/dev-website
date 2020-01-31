<?php

namespace App\Http\Transformers;

use App\Model\OParl10Body;

class BodyTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['legislativeTerm', 'location'];

    public function transform(OParl10Body $body)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($body), [
            'system'            => route('api.oparl.v1.system.show', $body->system),
            'shortName'         => $body->short_name,
            'name'              => $body->name,
            'website'           => $body->website,
            'license'           => $body->license,
            'licenseValidSince' => ($body->license_valid_since) ? $this->formatDateTime($body->license_valid_since) : null,
            'oparlSince'        => ($body->oparl_since) ? $this->formatDateTime($body->oparl_since) : null,
            'ags'               => $body->ags,
            'rgs'               => $body->rgs,
            'equivalent'        => $body->equivalent_body,
            'contactEmail'      => $body->contact_email,
            'contactName'       => $body->contact_name,
            'organization'      => route_where('api.oparl.v1.organization.index', ['body' => $body->id]),
            'person'            => route_where('api.oparl.v1.person.index', ['body' => $body->id]),
            'meeting'           => route_where('api.oparl.v1.meeting.index', ['body' => $body->id]),
            'paper'             => route_where('api.oparl.v1.paper.index', ['body' => $body->id]),
            // legislative term is an included object
            'classification' => $body->classification,
            // location is an included object
        ]);

        return $this->cleanupData($data, $body);
    }

    public function includeLegislativeTerm(OParl10Body $body)
    {
        return $this->collection($body->legislativeTerms, new LegislativeTermTransformer(true), 'included');
    }

    public function includeLocation(OParl10Body $body)
    {
        if (!$body->location) {
            return;
        }

        return $this->item($body->location, new LocationTransformer(true));
    }
}
