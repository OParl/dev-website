<?php

namespace App\Http\Controllers;

use App\Http\Requests\DownloadRequest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use OParl\Spec\Repositories\SpecificationDownloadRepository;

class DownloadsController extends Controller
{
    public function index(SpecificationDownloadRepository $specificationDownloadRepository)
    {
        return view('downloads.index', [
            'title'                  => trans('app.downloads.title'),
            'specificationDownloads' => $specificationDownloadRepository,
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

    /**
     * Handle specification download requests from the web form
     *
     * @param DownloadRequest $downloadRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadRequest(DownloadRequest $downloadRequest)
    {
        $input = $downloadRequest->except('_token');

        $selectedVersion = $input['version'];
        $selectedFormat = $input['format'][$selectedVersion];

        if (in_array($selectedFormat, ['bz2', 'gz'])) {
            $selectedFormat = 'tar.' . $selectedFormat;
        }

        return redirect()->route('downloads.specification',
            [
                'version' => $selectedVersion,
                'format'  => $selectedFormat,
            ]
        );
    }
}
