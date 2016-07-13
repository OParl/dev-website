<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Organization;

class OrganizationTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['location'];

    public function transform(Organization $organization)
    {
        return [
            'id'                => route('api.v1.organization.show', $organization),
            'type'              => 'https://schema.oparl.org/1.0/Organization',
            'body'              => route('api.v1.body.show', $organization->body_id),
            'name'              => $organization->name,
            'shortName'         => $organization->short_name,
            'membership'        => $this->collectionRouteList('api.v1.membership.show', $organization->members),
            'meeting'           => route_where('api.v1.meeting.index', ['organization' => $organization->id]),
            'post'              => $organization->post,
            'subOrganizationOf' => null, // TODO: sub orgas
            'organizationType'  => $organization->organizationType,
            'classification'    => $organization->classification,
            'startDate'         => $this->formatDate($organization->start_date),
            'endDate'           => $this->formatDate($organization->end_date),
            'website'           => $organization->website,
            // location is included
            // TODO: external body
            'keyword'           => [],//$organization->keywords->pluck('human_name'),
            'created'           => $this->formatDate($organization->created_at),
            'modified'          => $this->formatDate($organization->updated_at),
            'deleted'           => $organization->trashed(),
        ];
    }

    public function includeLocation(Organization $organization)
    {
        return $this->item($organization->location, new LocationTransformer(true));
    }
}
