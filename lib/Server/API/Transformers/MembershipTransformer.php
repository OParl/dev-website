<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Membership;

class MembershipTransformer extends BaseTransformer
{
    public function transform(Membership $membership)
    {
        $data = [
            'id'           => route('api.v1.membership.show', $membership),
            'type'         => 'https://schema.oparl.org/1.0/Membership',
            'organization' => route('api.v1.organization.show', $membership->organization),
            'role'         => $membership->role,
            'votingRight'  => $membership->voting_right,
            'startDate'    => $this->formatDate($membership->start_date),
            'endDate'      => $this->formatDate($membership->end_date),
            // TODO: on_behalf_of
            'keyword'      => $membership->keywords->pluck('human_name'),
            'created'      => $this->formatDate($membership->created_at),
            'modified'     => $this->formatDate($membership->updated_at),
            'deleted'      => $membership->trashed(),
        ];

        if (!$this->isIncluding()) {
            $data['person'] = route('api.v1.person.show', $membership->person);
        }

        return $data;
    }
}
