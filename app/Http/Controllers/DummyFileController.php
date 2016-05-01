<?php namespace App\Http\Controllers;

class DummyFileController extends Controller
{
    public function serve($filename)
    {
        $dataPath = base_path('resources/documents/dummyfile.pdf');
        $data = file_get_contents($dataPath);

        return response($data, 200, [
            'Content-type'        => 'application/pdf',
            'Content-disposition' => "attachment; filename={$filename}"
        ]);
    }

    public function show()
    {
        $dataPath = base_path('resources/documents/dummyfile.pdf');
        $data = file_get_contents($dataPath);

        return response($data, 200, ['Content-type' => 'application/pdf']);
    }
}