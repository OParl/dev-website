<?php

namespace App\Http\Controllers\API;

use App\Model\Endpoint;
use Illuminate\Http\Request;

class EndpointApiController
{
    /**
     * @OA\Get(
     *     path="/api/endpoints",
     *     tags={ "endpoints" },
     *     summary="list endpoints",
     *     @OA\Response(
     *         response="200",
     *         description="A listing of known OParl endpoints",
     *         @OA\Schema(
     *              @OA\Property( property="data", type="array", @OA\Items( ref="#/definitions/Endpoint" ))
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="page",
     *         description="Page of the result set",
     *         example="page=1"
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="include",
     *         description="Include related resources, currently only used for known OParl:Body resources on the endpoint",
     *         example="include=bodies"
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

        if ($request->has('include') && 'bodies' === $request->get('include')) {
            $endpoints = Endpoint::with('bodies')->get()->forPage($page, $itemsPerPage);
        } else {
            $endpoints = Endpoint::all()->forPage($page, $itemsPerPage);
        }

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

    /**
     * @OA\Get(
     *     path="/api/endpoint/{endpointId}",
     *     tags={ "endpoints" },
     *     summary="Information about a single endpoint",
     *     @OA\Response(
     *         response="200",
     *         description="A listing of known OParl endpoints",
     *         @OA\Schema(
     *              @OA\Property( property="data", type="array", @OA\Items( ref="#/definitions/Endpoint" ))
     *         )
     *     ),
     *     @OA\Parameter(
     *         in="path",
     *         name="endpointId",
     *         description="Id of the queried endpoint"
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="include",
     *         description="Include related resources, currently only used for known OParl:Body resources on the endpoint",
     *         example="include=bodies"
     *     )
     * )
     *
     * @param $id
     */
    public function endpoint(Request $request, $id)
    {
        if ($request->has('include') && 'bodies' === $request->get('include')) {
            $endpoint = Endpoint::with('bodies')->find($id);
        } else {
            $endpoint = Endpoint::find($id);
        }

        return response()->json([
            'data' => $endpoint,
            'meta' => []
        ], 200, [
            'Content-Type'                => 'application/json',
            'Access-Control-Allow-Origin' => '*',
        ]);
    }
}
