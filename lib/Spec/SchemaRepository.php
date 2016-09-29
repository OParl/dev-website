<?php

namespace OParl\Spec;

use Illuminate\Contracts\Filesystem\Filesystem;

class SchemaRepository
{
    protected $entities = [
        'AgendaItem',
        'System',
        'Body',
        'LegislativeTerm',
        'Person',
        'Organization',
        'Location',
        'File',
        'Membership',
        'Meeting',
        'Paper',
        'Consultation',
    ];

    protected $fs = null;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    public function getEntityForVersion($entityName, $version = 'latest')
    {
        if ($version === 'latest') {
            $entityPath = "live_version/schema/{$entityName}.json";

            if (!$this->fs->exists($entityPath)) {
                throw new SpecificationException("Entity {$entityName} was not found.");
            }

            $loadedEntity = json_decode($fs->get($entityPath), true);
        }
    }

    public function validate($entityName)
    {
        return in_array($entityName, $this->entities);
    }
}
