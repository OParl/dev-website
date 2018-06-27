<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use OParl\Spec\Model\LiveView;

class SpecificationController extends Controller
{
    /**
     * Show the specification's live copy.
     *
     * @param LiveView $liveView
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
            return response(
                "{$image} was not found on the server.",
                404,
                ['Content-type' => 'text/plain']
            );
        }

        return response($imageData, 200, ['Content-type' => 'image/png']);
    }

    public function raw(LiveView $liveView)
    {
        try {
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
        // TODO: implement multiple live view versions
        return $this->redirectToIndex();
    }

    public function redirectToIndex()
    {
        return redirect()->route('specification.index');
    }
}
