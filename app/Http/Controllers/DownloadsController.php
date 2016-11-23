<?php

namespace App\Http\Controllers;

use App\Http\Requests\VersionSelectRequest;
use OParl\Spec\BuildRepository;
use OParl\Spec\Repositories\SpecificationDownloadRepository;

class DownloadsController extends Controller
{
    public function index(SpecificationDownloadRepository $specificationDownloadRepository)
    {
        return view('downloads.index', [
            'title'  => 'Downloads',
            'latestSpecificationDownload' => $specificationDownloadRepository->getLatest()
        ]);
    }

    public function latest($extension, BuildRepository $buildRepository)
    {
        return redirect(null, 302)->route('downloads.provide', [
            $buildRepository->getLatest()->short_hash,
            $extension,
        ]);
    }
}
