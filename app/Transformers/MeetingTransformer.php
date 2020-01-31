<?php

namespace App\Transformers;

use App\Model\OParl10Meeting;

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

    public function transform(OParl10Meeting $meeting)
    {
        $data = array_merge($this->getDefaultAttributesForEntity($meeting), [
            'name'         => $meeting->name,
            'meetingState' => $meeting->meeting_state,
            'cancelled'    => $meeting->cancelled,
            'start'        => $this->formatDateTime($meeting->start),
            'end'          => $this->formatDateTime($meeting->end),
            // location is included
            'organization' => $this->collectionRouteList('api.oparl.v1.organization.show', $meeting->organizations),
            'participant'  => $this->collectionRouteList('api.oparl.v1.person.show', $meeting->participants),
            // invitation is included
            // resultsProtocol is included
            // verbatimProtocol is included
            // auxiliaryFile is included
            // agendaItem is included
        ]);

        return $this->cleanupData($data, $meeting);
    }

    public function includeLocation(OParl10Meeting $meeting)
    {
        if (!$meeting->location) {
            return;
        }

        return $this->item($meeting->location, $this->locationTransformer);
    }

    public function includeInvitation(OParl10Meeting $meeting)
    {
        if (!$meeting->invitation) {
            return;
        }

        return $this->item($meeting->invitation, $this->fileTransformer);
    }

    public function includeResultsProtocol(OParl10Meeting $meeting)
    {
        if (!$meeting->resultsProtocol) {
            return;
        }

        return $this->item($meeting->resultsProtocol, $this->fileTransformer);
    }

    public function includeVerbatimProtocol(OParl10Meeting $meeting)
    {
        if (!$meeting->verbatimProtocol) {
            return;
        }

        return $this->item($meeting->verbatimProtocol, $this->fileTransformer);
    }

    public function includeAuxiliaryFile(OParl10Meeting $meeting)
    {
        return $this->collection($meeting->auxiliaryFiles, $this->fileTransformer, 'included');
    }

    public function includeAgendaItem(OParl10Meeting $meeting)
    {
        return $this->collection($meeting->agendaItems, $this->agendaItemTransformer, 'included');
    }
}
