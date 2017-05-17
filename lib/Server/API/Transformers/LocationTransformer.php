<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\Location;

class LocationTransformer extends BaseTransformer
{
    public function transform(Location $location)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($location), [
            'description'   => $location->description,
            'geojson'       => $location->geojson,
            'streetAddress' => $location->street_address,
            'room'          => $location->room,
            'postalCode'    => $location->postal_code,
            'subLocality'   => $location->sub_locality,
            'locality'      => $location->locality,
        ]);

        if (!$this->isIncluded()) {
            $data = array_merge($data, [
                'body'         => $this->collectionRouteList('api.oparl.v1.body.show', $location->bodies),
                'organization' => $this->collectionRouteList('api.oparl.v1.organization.show', $location->organizations),
                'person'       => $this->collectionRouteList('api.oparl.v1.person.show', $location->people),
                'meeting'      => $this->collectionRouteList('api.oparl.v1.meeting.show', $location->meetings),
                'paper'        => $this->collectionRouteList('api.oparl.v1.paper.show', $location->papers),
            ]);
        }

        return $this->cleanupData($data, $location);
    }
}
