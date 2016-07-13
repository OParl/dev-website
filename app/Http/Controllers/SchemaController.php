<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;

use App\Http\Requests;

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
                'Consultation'
            ]
        )) {
            abort(404);
        }

        // rewrite embedded entities
        if ($entity === 'LegislativeTerm') $entity = 'Body';
        if ($entity === 'Membership') $entity = 'Organization';
        if ($entity === 'AgendaItem') $entity = 'Meeting';
        if ($entity === 'Consultation') $entity = 'Paper';

        $entityPath = "live_version/schema/{$entity}.json";

        if (!$fs->exists($entityPath)) {
            abort(404, 'The requested schema was not found on this server');
        }

        return response()->json(json_decode($fs->get($entityPath), true));
    }
}
