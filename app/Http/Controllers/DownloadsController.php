<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use OParl\Spec\Repositories\SpecificationDownloadRepository;

class DownloadsController extends Controller
{
    public function index()
    {
        return view('downloads.index', [
            'title' => trans('app.downloads.title'),
        ]);
    }

    public function specification(
        SpecificationDownloadRepository $specificationDownloadRepository,
        $format
    ) {
        /* @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = \Validator::make(compact('format'), [
           'format' => 'in:pdf,txt,odt,docx,html,epub,zip,tar.gz,tar.bz2'
        ]);

        if ($validator->fails()) {
            return response('Invalid request.', 403, ['Content-type' => 'text/plain']);
        }

        try {
            $file = $specificationDownloadRepository->getLatest()->getFileForExtension($format);
            return response()->download($file->getInfo()->getRealPath());
        } catch (FileNotFoundException $e) {
            return response('File not found.', 404, ['Content-type' => 'text/plain']);
        }
    }
}
