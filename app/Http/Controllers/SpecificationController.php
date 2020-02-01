<?php

namespace App\Http\Controllers;

use App\Exceptions\LiveViewException;
use App\Repositories\LiveViewRepository;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\File\Stream;

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
        if (null === $version) {
            $version = config('oparl.specificationDisplayVersion');
        }

        $title = 'Spezifikation '.$version;

        // TODO: handle live view not loadable exception
        $liveView = $this->liveViewRepository->get($version);

        return view('specification.index', compact('title', 'liveView'));
    }

    public function imageIndex()
    {
        abort(404);
    }

    public function image($version, $image)
    {
        $imageNotFoundResponse = response(
            "{$image} was not found on the server.",
            404,
            ['Content-type' => 'text/plain']
        );

        try {
            $liveView = $this->liveViewRepository->get($version);
        } catch (LiveViewException $e) {
            return $imageNotFoundResponse;
        }

        if (!$liveView->hasImage($image)) {
            return $imageNotFoundResponse;
        }

        return response()->file($liveView->getImage($image));
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
