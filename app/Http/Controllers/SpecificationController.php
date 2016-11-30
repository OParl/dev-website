<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\BuildRepository;
use OParl\Spec\LiveVersionRepository;
use OParl\Spec\Model\LiveView;
use OParl\Spec\Repositories\DownloadRepository;

class SpecificationController extends Controller
{
    /**
     * Show the specification's live copy.
     *
     * @param LiveVersionRepository $liveversion
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LiveView $liveView)
    {
        $title = 'Spezifikation';

        return view('specification.index', compact('title', 'liveView'));
    }

    public function imageIndex()
    {
        abort(404);
    }

    public function image(LiveView $liveView, $image)
    {
        try {
            $imageData = $liveView->getImage($image);
        } catch (FileNotFoundException $e) {
            return response("{$image} was not found on the server.", 404, ['Content-type' => 'text/plain']);
        }

        return response($imageData, 200, ['Content-type' => 'image/png']);
    }

    public function raw(LiveView $liveView)
    {
        return response($liveView->getRaw(), 200, ['Content-type' => 'text/plain']);
    }

    public function redirectToIndex()
    {
        return redirect()->route('specification.index');
    }

    public function redirectToVersion($version)
    {
        // TODO: implement multiple live view versions
        return $this->redirectToIndex();
    }
}
