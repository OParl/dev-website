<?php

namespace OParl\Server\API\Transformers;

use OParl\Server\Model\File;
use OParl\Server\Model\Location;
use OParl\Server\Model\Meeting;

class MeetingTransformer extends BaseTransformer
{
    protected $defaultIncludes = [
        'location',
        'invitation',
        'resultsProtocol',
        'verbatimProtocol',
        'auxiliaryFile',
        'agendaItem',
    ];

    protected $locationTransformer;
    protected $fileTransformer;
    protected $agendaItemTransformer;

    public function __construct($included = false)
    {
        parent::__construct($included);

        $this->locationTransformer = (new LocationTransformer())->setIncluded(true);
        $this->fileTransformer = (new FileTransformer())->setIncluded(true);
        $this->agendaItemTransformer = (new AgendaItemTransformer())->setIncluded(true);
    }

    public function transform(Meeting $meeting)
    {
        return array_merge($this->getDefaultAttributesForEntity($meeting), [
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
        ]);
    }

    public function includeLocation(Meeting $meeting)
    {
        if (!$meeting->location) {
            return null;
        }

        return $this->item($meeting->location, $this->locationTransformer);
    }

    public function includeInvitation(Meeting $meeting)
    {
        if (!$meeting->invitation instanceof File) {
            return $this->item($meeting->invitation, $this->fileTransformer);
        }
    }

    public function includeResultsProtocol(Meeting $meeting)
    {
        if ($meeting->resultsProtocol instanceof File) {
            return $this->item($meeting->resultsProtocol, $this->fileTransformer);
        }
    }

    public function includeVerbatimProtocol(Meeting $meeting)
    {
        if ($meeting->verbatimProtocol instanceof File) {
            return $this->item($meeting->verbatimProtocol, $this->fileTransformer);
        }
    }

    public function includeAuxiliaryFile(Meeting $meeting)
    {
        return $this->collection($meeting->auxiliaryFiles, $this->fileTransformer, 'included');
    }

    public function includeAgendaItem(Meeting $meeting)
    {
        return $this->collection($meeting->agendaItems, $this->agendaItemTransformer, 'included');
    }
}
