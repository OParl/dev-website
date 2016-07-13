<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Location;

class LocationTransformer extends BaseTransformer
{
    public function transform(Location $location)
    {
        $data = [
            'id'            => route('api.v1.location.show', $location),
            'type'          => 'https://schema.oparl.org/1.0/Location',
            'description'   => $location->description,
            'geojson'       => $location->geojson,
            'streetAddress' => $location->street_address,
            'room'          => $location->room,
            'postalCode'    => $location->postal_code,
            'subLocality'   => $location->sub_locality,
            'locality'      => $location->locality,
            'keyword'       => $location->keywords->pluck('human_name'),
            'created'       => $this->formatDate($location->created_at),
            'modified'      => $this->formatDate($location->updated_at),
            'deleted'       => $location->trashed(),
        ];

        if ($this->isIncluding()) {
            $data = array_merge($data, [
                'body'         => $this->collectionRouteList('api.v1.body.show', $location->bodies),
                'organization' => $this->collectionRouteList('api.v1.organization.show', $location->organizations),
                'person'       => $this->collectionRouteList('api.v1.person.show', $location->people),
                'meeting'      => $this->collectionRouteList('api.v1.meeting.show', $location->meetings),
                'paper'        => $this->collectionRouteList('api.v1.paper.show', $location->papers),
            ]);
        }

        return $data;
    }
}
