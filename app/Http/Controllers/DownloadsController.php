<?php namespace App\Http\Controllers;

use App\Http\Requests\VersionSelectRequest;
use OParl\Spec\BuildRepository;

class DownloadsController extends Controller
{
    public function index(BuildRepository $buildRepository)
    {
        return view('downloads.index', [
            'builds' => $buildRepository->getLatest(15),
            'title' => 'Downloads'
        ]);
    }

    public function latest($extension, BuildRepository $buildRepository)
    {
        return redirect(null, 302)->route('downloads.provide', [
            $buildRepository->getLatest()->short_hash,
            $extension
        ]);
    }

    public function getFile($short_hash, $extension, BuildRepository $buildRepository)
    {
        $build = $buildRepository->getWithShortHash($short_hash);

        $file = null;

        if (in_array($extension, ['zip', 'tar.gz', 'tar.bz'])) {
            $property = sprintf('%s_storage_path', str_replace('.', '_', $extension));

            $file = new \SplFileInfo($build->{$property});
        }

        if (in_array($extension, ['docx', 'txt', 'pdf', 'odt', 'html', 'epub'])) {
            $file = new \SplFileInfo($build->discoverExtractedFile($extension));
        }

        if (is_null($file) || !$file->isFile()) {
            abort(404, 'Die angefragte Datei wurde auf diesem Server nicht gefunden.');
        } else {
            if ($buildRepository->isLatest($build)) {
                $filename = $file->getBasename();
            } else {
                $filename = sprintf(
                    '%s-%s.%s',
                    $file->getBasename($file->getExtension()),
                    $build->short_hash,
                    $extension);
            }

            return response()->download($file, $filename);
        }
    }

    public function selectVersion(VersionSelectRequest $request)
    {
        // redirect to download link
        return redirect(null, 302)->route('downloads.provide', [
            $request->input('version'),
            $request->input('format')
        ]);
    }
}
