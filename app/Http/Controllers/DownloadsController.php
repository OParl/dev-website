<?php

namespace App\Http\Controllers;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
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
        $version,
        $format
    ) {
        /* @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = \Validator::make(compact('format'), [
            'version' => 'string',
            'format' => 'in:pdf,txt,odt,docx,html,epub,zip,tar.gz,tar.bz2',
        ]);

        abort_if($validator->fails(), 403);
        try {
            $download = $specificationDownloadRepository->getLatest();

            if (strcmp($version, 'latest') !== 0) {
                $download = $specificationDownloadRepository->getVersion($version);
            }

            $file = $download->getFileForExtension($format);
            return response()->download($file->getInfo()->getRealPath());
        } catch (FileNotFoundException $e) {
            abort(404);
        }
    }
}
