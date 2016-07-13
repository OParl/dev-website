<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Person;

class PersonTransformer extends BaseTransformer
{
    protected $defaultIncludes = ['location', 'membership'];

    public function transform(Person $person)
    {
        return [
            'id'            => route('api.v1.person.show', $person),
            'type'          => 'https://schema.oparl.org/1.0/Person',
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
            'keyword'       => $person->keywords->pluck('human_name'),
            'created'       => $this->formatDate($person->created_at),
            'modified'      => $this->formatDate($person->updated_at),
            'deleted'       => $person->trashed(),
        ];
    }

    public function includeLocation(Person $person)
    {
        return $this->item($person->location, new LocationTransformer(true));
    }

    public function includeMembership(Person $person)
    {
        return $this->collection($person->memberships, new MembershipTransformer(true));
    }
}
