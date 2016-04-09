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
            'type'          => 'http://spec.oparl.org/spezifikation/1.0/#entity-location',
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
                // TODO: collection route lists for bodies, organizations, people, meetings and papers
                //       that use the current location
            ]);
        }

        return $data;
    }
}
