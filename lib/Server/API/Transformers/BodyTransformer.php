<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Body;
use OParl\Server\Model\LegislativeTerm;

class BodyTransformer extends BaseTransformer
{
    protected $availableIncludes = ['legislativeTerm', 'location'];
    protected $defaultIncludes = ['legislativeTerm', 'location'];

    public function transform(Body $body)
    {
        return [
            'id'                => route('api.v1.body.show', $body),
            'type'              => 'https://spec.oparl.org/spezifikation/1.0/#body-entity',
            'system'            => route('api.v1.system.show', $body->system),
            'shortName'         => $body->short_name,
            'name'              => $body->name,
            'website'           => $body->website,
            'license'           => $body->license,
            'licenseValidSince' => ($body->license_valid_since) ? $this->formatDate($body->license_valid_since) : null,
            'oparlSince'        => ($body->oparl_since) ? $this->formatDate($body->oparl_since) : null,
            'ags'               => $body->ags,
            'rgs'               => $body->rgs,
            'equivalent'    => $body->equivalent_body,
            'contactEmail'      => $body->contact_email,
            'contactName'       => $body->contact_name,
            'organization'      => route_where('api.v1.organization.index', ['body' => $body->id]),
            'person'            => route_where('api.v1.person.index', ['body' => $body->id]),
            'meeting'           => route_where('api.v1.meeting.index', ['body' => $body->id]),
            'paper'             => route_where('api.v1.paper.index', ['body' => $body->id]),
            // legislative term is an included object
            'classification'    => $body->classification,
            // location is an included object
            'keyword'           => $body->keywords,
            'created'           => $this->formatDate($body->created_at),
            'modified'          => $this->formatDate($body->updated_at),
            'deleted'           => ($body->trashed()) ? true : false,
        ];
    }

    public function includeLegislativeTerm(Body $body)
    {
        // TODO: drop the body_id fields out of this
        return $this->collection($body->legislativeTerms, new LegislativeTermTransformer());
    }

    public function includeLocation(Body $body) {
        return $this->item($body->location, new LocationTransformer());
    }
}
