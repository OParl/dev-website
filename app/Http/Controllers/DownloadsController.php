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
        $version,
        $format
    ) {
        /* @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = \Validator::make(compact('format'), [
            'version' => 'string',
            'format'  => 'in:pdf,txt,odt,docx,html,epub,zip,tar.gz,tar.bz2',
        ]);

        abort_if($validator->fails(), 403);

        if (strcmp($version, 'latest') !== 0) {
            $download = $specificationDownloadRepository->getVersion($version);
        } else {
            $download = $specificationDownloadRepository->getVersion('master');
        }

        abort_if(is_null($download), 404);

        try {
            $file = $download->getFileForExtension($format);

            return response()->download($file->getInfo()->getRealPath());
        } catch (FileNotFoundException $e) {
            abort(404);
        }

        return redirect()->back();
    }

    public function latestSpecification($format)
    {
        return redirect()->route('downloads.specification', ['latest', $format]);
    }
}
