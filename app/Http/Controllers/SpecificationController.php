<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use OParl\Spec\Repositories\LiveViewRepository;

class SpecificationController extends Controller
{
    /**
     * Show the specification's live copy.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Spezifikation';

        return view('specification.index', compact('title'));
    }

    public function imageIndex()
    {
        abort(404);
    }

    public function image(LiveViewRepository $liveViewRepository, $version, $image)
    {
        try {
            $liveView = $liveViewRepository->get($version);
            $imageData = $liveView->getImage($image);
        } catch (FileNotFoundException $e) {
            return response(
                "{$image} was not found on the server.",
                404,
                ['Content-type' => 'text/plain']
            );
        }

        return response($imageData, 200, ['Content-type' => 'image/png']);
    }

    public function raw(LiveViewRepository $liveViewRepository, $version)
    {
        try {
            $liveView = $liveViewRepository->get($version);
            return response($liveView->getRaw(), 200, ['Content-type' => 'text/plain']);
        } catch (FileNotFoundException $e) {
            return response(
                "Failed to fetch raw markdown specification, sorry.",
                404,
                ['Content-type' => 'text/plain']
            );
        }
    }

    public function redirectToVersion($version)
    {
        return redirect()->route('specification.index', ['version' => $version]);
    }

    public function redirectToIndex()
    {
        return redirect()->route('specification.index');
    }
}
