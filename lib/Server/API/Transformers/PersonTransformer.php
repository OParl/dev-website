<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\Person;

class PersonTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['location', 'membership'];

    public function transform(Person $person)
    {
        return remove_empty_keys(array_merge($this->getDefaultAttributesForEntity($person), [
            'body'          => route('api.v1.body.show', $person->body_id),
            'name'          => $person->name,
            'familyName'    => $person->family_name,
            'givenName'     => $person->given_name,
            'formOfAddress' => $person->form_of_address,
            'title'         => $person->title,
            'gender'        => $person->gender,
            'phone'         => $person->phone,
            'email'         => $person->email,
            // location is included
            'status'        => $person->status,
            // membership is included
            'life'          => $person->life,
            'lifeSource'    => $person->life_source,
        ]));
    }

    public function includeLocation(Person $person)
    {
        if (!$person->location) {
            return null;
        }

        return $this->item($person->location, new LocationTransformer(true));
    }

    public function includeMembership(Person $person)
    {
        return $this->collection($person->memberships, new MembershipTransformer(true), 'included');
    }
}
