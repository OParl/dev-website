<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace OParl\Website\API\Controllers;


use OParl\Spec\Model\LiveView;

class SpecificationApiController extends ApiController
{
    /**
     * @param LiveView $liveView
     * @param          $version
     * @return \Illuminate\Http\JsonResponse
     */
    public function version(LiveView $liveView, $version)
    {
        return response()->json([
            'currentVersion' => $liveView->getVersionInformation()['official'],
            'toc'            => $liveView->getTableOfContents(),
            'body'           => $liveView->getBody(),
        ]);
    }
}
