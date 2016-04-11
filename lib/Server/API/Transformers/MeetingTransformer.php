<?php

namespace OParl\Server\API\Transformers;

use EFrane\Transfugio\Transformers\BaseTransformer;
use OParl\Server\Model\Meeting;

class MeetingTransformer extends BaseTransformer
{
    protected $defaultIncludes = [
        'location',
        'invitation',
        'resultsProtocol',
        'verbatimProtocol',
        'auxiliaryFile',
        'agendaItem'
    ];

    public function transform(Meeting $meeting)
    {
        return [
            'id'           => route('api.v1.meeting.index', $meeting),
            'type'         => 'http://spec.oparl.org/spezifikation/1.0/#entity-meeting',
            'name'         => $meeting->name,
            'meetingState' => $meeting->meeting_state,
            'cancelled'    => $meeting->cancelled,
            'start'        => $this->formatDate($meeting->start),
            'end'          => $this->formatDate($meeting->end),
            // location is included
            'organization' => $this->collectionRouteList('api.v1.organization.show', $meeting->organizations),
            'participant'  => $this->collectionRouteList('api.v1.person.show', $meeting->participants),
            // invitation is included
            // resultsProtocol is included
            // verbatimProtocol is included
            // auxiliaryFile is included
            // agendaItem is included
            'keyword'      => $meeting->keywords->pluck('human_name'),
            'created'      => $this->formatDate($meeting->created_at),
            'modified'     => $this->formatDate($meeting->updated_at),
            'deleted'      => $meeting->trashed(),
        ];
    }

    public function includeLocation(Meeting $meeting)
    {
        return $this->item($meeting->location, new LocationTransformer(true));
    }

    public function includeInvitation(Meeting $meeting)
    {
        return $this->item($meeting->invitation, new FileTransformer(true));
    }

    public function includeResultsProtocol(Meeting $meeting)
    {
        return $this->item($meeting->resultsProtocol, new FileTransformer(true));
    }

    public function includeVerbatimProtocol(Meeting $meeting)
    {
        return $this->item($meeting->verbatimProtocol, new FileTransformer(true));
    }

    public function includeAuxiliaryFile(Meeting $meeting)
    {
        return $this->collection($meeting->auxiliaryFiles, new FileTransformer(true));
    }

    public function includeAgendaItem(Meeting $meeting)
    {
        return $this->collection($meeting->agendaItems, new AgendaItemTransformer(true));
    }
}
