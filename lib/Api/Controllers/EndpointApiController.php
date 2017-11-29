<?php

namespace OParl\Website\API\Controllers;

use App\Model\Endpoint;
use Illuminate\Http\Request;

class EndpointApiController
{
    /**
     * @SWG\Get(
     *     path="/endpoints",
     *     tags={ "endpoints" },
     *     summary="list endpoints",
     *     @SWG\Response(
     *         response="200",
     *         description="A listing of known OParl endpoints",
     *         @SWG\Schema(
     *              @SWG\Property( property="data", type="array", @SWG\Items( ref="#/definitions/Endpoint" ))
     *         )
     *     )
     * )
     *
     * @SWG\Definition(
     *     type="object",
     *     definition="Endpoint",
     *     example={
     *         "url": "https://example.com/api",
     *         "title": "Example.com OParl API",
     *         "description": "This is a cool OParl API"
     *     },
     *     required={
     *         "url",
     *         "title"
     *     },
     *     @SWG\Property(
     *         property="url",
     *         type="string",
     *         description="The OParl endpoint's entrypoint"
     *     ),
     *     @SWG\Property(
     *         property="title",
     *         type="string",
     *         description="The OParl endpoint's name"
     *     ),
     *     @SWG\Property(
     *         property="description",
     *         type="string",
     *         description="Optional detailed endpoint description"
     *     )
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = 0;
        $itemsPerPage = 25;

        if ($request->has('page')) {
            $page = intval($request->get('page'));
        }

        $endpoints = Endpoint::with('bodies')->get()->forPage($page, $itemsPerPage);
        $pageCount = floor($endpoints->count() / $itemsPerPage);

        return response()->json([
            'data' => $endpoints,
            'meta' => [
                'page'  => $page,
                'total' => $pageCount,
                'next'  => ($pageCount > $page)
                    ? route('api.endpoints.index', ['page' => ++$page])
                    : null,
            ],
        ]);
    }
}
