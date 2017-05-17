<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\Organization;

class OrganizationTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['location'];

    public function transform(Organization $organization)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($organization), [
            'body'              => route('api.oparl.v1.body.show', $organization->body_id),
            'name'              => $organization->name,
            'shortName'         => $organization->short_name,
            'membership'        => $this->collectionRouteList('api.oparl.v1.membership.show', $organization->members),
            'meeting'           => route_where('api.oparl.v1.meeting.index', ['organization' => $organization->id]),
            'post'              => $organization->post,
            'subOrganizationOf' => null, // TODO: sub orgas
            'organizationType'  => $organization->organizationType,
            'classification'    => $organization->classification,
            'startDate'         => $this->formatDate($organization->start_date),
            'endDate'           => $this->formatDate($organization->end_date),
            'website'           => $organization->website,
            // location is included
            // TODO: external body
        ]);

        return $this->cleanupData($data, $organization);
    }

    public function includeLocation(Organization $organization)
    {
        if (!$organization->location) {
            return;
        }

        return $this->item($organization->location, new LocationTransformer(true));
    }
}
