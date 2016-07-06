<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\BuildRepository;
use OParl\Spec\LiveVersionRepository;

class SpecificationController extends Controller
{
    /**
     * Show the specification's live copy.
     *
     * @param LiveVersionRepository $liveversion
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LiveVersionRepository $liveversion, Guard $guard)
    {
        $title = 'Spezifikation';
        $isLoggedIn = $guard->check();

        return view('specification.index', compact('liveversion', 'title', 'isLoggedIn'));
    }

    public function builds(BuildRepository $build)
    {
        return response()->json($build->getLatest(15));
    }

    public function imageIndex()
    {
        abort(404);
    }

    public function image(Filesystem $fs, $image)
    {
        try {
            $imageData = $fs->get(LiveVersionRepository::getImagesPath($image));
        } catch (FileNotFoundException $e) {
            return response("{$image} was not found on the server.", 404);
        }

        return response($imageData, 200, ['Content-type' => 'image/png']);
    }

    public function raw(LiveVersionRepository $livecopy)
    {
        return response($livecopy->getRaw(), 200, ['Content-type' => 'text/plain']);
    }
}
