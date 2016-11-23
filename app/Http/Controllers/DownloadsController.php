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
            'format' => 'required|in:pdf,txt,odt,docx,html,epub,zip,tar.gz,tar.bz2',
        ]);

        abort_if($validator->fails(), 403);

        try {
            $file = $specificationDownloadRepository->getLatest()->getFileForExtension($format);
            return response()->download($file->getInfo()->getRealPath());
        } catch (FileNotFoundException $e) {
            abort(404);
        }
    }
}
