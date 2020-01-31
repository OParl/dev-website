<?php

namespace App\Http\Transformers;

use App\Model\OParl10Person;

class PersonTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['membership'];

    public function transform(OParl10Person $person)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($person), [
            'body'          => route('api.oparl.v1.body.show', $person->body_id),
            'name'          => $person->name,
            'familyName'    => $person->family_name,
            'givenName'     => $person->given_name,
            'formOfAddress' => $person->form_of_address,
            'title'         => $person->title,
            'gender'        => $person->gender,
            'phone'         => $person->phone,
            'email'         => $person->email,
            'location'      => ($person->location) ? route('api.oparl.v1.location.show', $person->location) : null,
            'status'        => $person->status,
            // membership is included
            'life'       => $person->life,
            'lifeSource' => $person->life_source,
        ]);

        return $this->cleanupData($data, $person);
    }

    public function includeLocation(OParl10Person $person)
    {
        if (!$person->location) {
            return;
        }

        return $this->item($person->location, new LocationTransformer(true));
    }

    public function includeMembership(OParl10Person $person)
    {
        return $this->collection($person->memberships, new MembershipTransformer(true), 'included');
    }
}
