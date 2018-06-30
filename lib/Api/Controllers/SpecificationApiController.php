<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace OParl\Website\API\Controllers;


use OParl\Spec\Repositories\LiveViewRepository;

class SpecificationApiController extends ApiController
{
    /**
     * @param LiveViewRepository $liveViewRepository
     * @param                    $version
     * @return \Illuminate\Http\JsonResponse
     */
    public function version(LiveViewRepository $liveViewRepository, $version)
    {
        $liveView = $liveViewRepository->get($version);

        return response()->json([
            'currentVersion' => $liveView->getVersionInformation()['official'],
            'toc'            => $liveView->getTableOfContents(),
            'body'           => $liveView->getBody(),
        ]);
    }
}
