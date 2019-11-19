<?php

namespace OParl\Website\API\Controllers;

use App\Model\Endpoint;
use Illuminate\Http\Request;

class EndpointApiController
{
    /**
     * @OA\Get(
     *     path="/endpoints",
     *     tags={ "endpoints" },
     *     summary="list endpoints",
     *     @OA\Response(
     *         response="200",
     *         description="A listing of known OParl endpoints",
     *         @OA\Schema(
     *              @OA\Property( property="data", type="array", @OA\Items( ref="#/definitions/Endpoint" ))
     *         )
     *     )
     * )
     *
     * @param Request $request
     *
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
        ], 200, [
            'Content-Type'                => 'application/json',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
