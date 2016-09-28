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

        $entityPath = "live_version/schema/{$entity}.json";

        if (!$fs->exists($entityPath)) {
            abort(404, 'The requested schema was not found on this server. Please check if you requested the correct schema version.');
        }

        $loadedEntity = json_decode($fs->get($entityPath), true);

        return response()->json($loadedEntity);
    }
}
