<?php

namespace App\Http\Controllers;

use App\Repositories\LiveViewRepository;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\View\View;

class SpecificationController extends Controller
{
    /**
     * @var LiveViewRepository
     */
    protected $liveViewRepository;

    /**
     * SpecificationController constructor.
     *
     * @param LiveViewRepository $liveViewRepository
     */
    public function __construct(LiveViewRepository $liveViewRepository)
    {
        $this->liveViewRepository = $liveViewRepository;
    }

    /**
     * Show the specification's live copy.
     *
     * @param                    $version
     * @return View
     */
    public function index(string $version = null)
    {
        $title = 'Spezifikation';

        if (null === $version) {
            $version = config('oparl.specificationDisplayVersion');
        }

        // TODO: handle live view not loadable exception
        $liveView = $this->liveViewRepository->get($version);
        $liveViewVersion = $liveView->getVersionInformation()['official'];

        return view('specification.index', compact('title', 'liveView', 'liveViewVersion'));
    }

    public function imageIndex()
    {
        abort(404);
    }

    public function image($version, $image)
    {
        try {
            $liveView = $this->liveViewRepository->get($version);
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

    public function raw($version)
    {
        try {
            $liveView = $this->liveViewRepository->get($version);

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
