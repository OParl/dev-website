<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Body;

class BodyTransformer extends BaseTransformer
{
    public function transform(Body $body)
    {
        return [
            'id'                => route('api.v1.body.show', $body),
            'type'              => 'https://spec.oparl.org/spezifikation/1.0/#body-entity',
            'system'            => route('api.v1.system.show', $body->system),
            'contactEmail'      => $body->contact_email,
            'contactName'       => $body->contact_name,
            'rgs'               => $body->rgs,
            'equivalentBody'    => $body->equivalent_body,
            'shortName'         => $body->short_name,
            'name'              => $body->name,
            'website'           => $body->website,
            'license'           => $body->license,
            'licenseValidSince' => ($body->license_valid_since) ? $this->formatDate($body->license_valid_since) : null,
            'organization'      => route_where('api.v1.organization.index', ['body' => $body->id]),
            'meeting'           => route_where('api.v1.meeting.index', ['body' => $body->id]),
            'paper'             => route_where('api.v1.paper.index', ['body' => $body->id]),
            'person'            => route_where('api.v1.person.index', ['body' => $body->id]),
            'agendaItem'        => route_where('api.v1.agendaitem.index', ['body' => $body->id]),
            'file'              => route_where('api.v1.file.index', ['body' => $body->id]),
            'consultation'      => route_where('api.v1.consultation.index', ['body' => $body->id]),
            'location'          => route_where('api.v1.location.index', ['body' => $body->id]),
            'membership'        => route_where('api.v1.membership.index', ['body' => $body->id]),
            'legislativeTerm'   => $this->collectionRouteList('api.v1.legislativeterm.show', $body->legislativeTerms),
            'classification'    => $body->classification,
            'keyword'           => $body->keywords,
            'created'           => $this->formatDate($body->created_at),
            'modified'          => $this->formatDate($body->updated_at)
        ];
    }
}
