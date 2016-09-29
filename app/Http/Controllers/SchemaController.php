<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Contracts\Filesystem\Filesystem;

class SchemaController extends Controller
{
    public function getSchema(Filesystem $fs, $entity)
    {
        if (!in_array(
            $entity,
            [
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
            ]
        )
        ) {
            abort(404);
        }

        if ($entity === 'LegislativeTerm') {
            return $this->load($fs, 'LegislativeTerm', 'Body');
        }

        if ($entity === 'Membership') {
            return $this->load($fs, 'Membership', 'Organization');
        }

        if ($entity === 'AgendaItem') {
            return $this->load($fs, 'AgendaItem', 'Meeting');
        }

        if ($entity === 'Consultation') {
            return $this->load($fs, 'Consultation', 'Paper');
        }

        return $this->load($fs, $entity);
    }

    // TODO: extract loading to SchemaRepository
    protected function load(Filesystem $fs, $entity, $parentEntity = '')
    {
        $entityToLoad = $entity;
        $hasParent = strlen($parentEntity) > 0;

        if ($hasParent) {
            $entityToLoad = $parentEntity;
        }
        $entityPath = "live_version/schema/{$entityToLoad}.json";

        if ($fs->exists($entityPath)) {
            $loadedEntity = json_decode($fs->get($entityPath), true);

            if ($hasParent) {
                $loadedEntity = $loadedEntity['properties'][lcfirst($entity)];
            }

            return response()->json($loadedEntity);
        } else {
            return abort(404, 'Entity not found.');
        }
    }
}
