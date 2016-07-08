<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;

use App\Http\Requests;

class SchemaController extends Controller
{
    public function getSchema(Filesystem $fs, $version, $entity)
    {
        abort_unless(
            strcmp($version, '1.0') == 0,
            404,
            'The requested schema version was not found on the server.'
        );

        $entityPath = "live_version/schema/{$entity}.json";

        if (!$fs->exists($entityPath)) {
            abort(404, 'The requested schema was not found on this server');
        }

        return response()->json(json_decode($fs->get($entityPath), true));
    }
}
