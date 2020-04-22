<?php

namespace App\Http\Controllers\API;

use App\Model\Endpoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EndpointApiController
{
    const DEFAULT_LIMIT = 25;

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
     *         name="limit",
     *         description="Number or results per page",
     *         example="limit=25"
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="include",
     *         description="Include related resources, currently only used for known OParl:Body resources on the
     *     endpoint", example="include=bodies"
     *     )
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $limit = self::DEFAULT_LIMIT;
        $page = 1;

        if ($request->has('limit')) {
            $requestedLimit = intval($request->get('limit'));
            $limit = ($requestedLimit < 100) ? $requestedLimit : $limit;
        }

        if ($request->has('page')) {
            $page = intval($request->get('page'));
        }

        if ($request->has('include') && 'bodies' === $request->get('include')) {
            $endpoints = Endpoint::with('bodies')->orderBy('title')->get()->forPage($page, $limit);
        } else {
            $endpoints = Endpoint::orderBy('title')->get()->forPage($page, $limit);
        }

        $pageCount = floor(Endpoint::count() / $limit);

        return response()->json(
            [
                'data' => array_values($endpoints->toArray()),
                'meta' => [
                    'page'  => $page,
                    'total' => $pageCount,
                    'self'  => route('api.endpoints.index', ['page' => $page, 'limit' => $limit]),
                    'next'  => ($pageCount > $page)
                        ? route('api.endpoints.index', ['page' => ++$page, 'limit' => $limit])
                        : null,
                ],
            ],
            Response::HTTP_OK,
            [
                'Content-Type'                => 'application/json',
                'Access-Control-Allow-Origin' => '*',
            ]
        );
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
     *         description="Include related resources, currently only used for known OParl:Body resources on the
     *     endpoint", example="include=bodies"
     *     )
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function endpoint(Request $request, $id)
    {
        if ($request->has('include') && 'bodies' === $request->get('include')) {
            $endpoint = Endpoint::with('bodies')->find($id);
        } else {
            $endpoint = Endpoint::find($id);
        }

        return response()->json(
            [
                'data' => $endpoint,
                'meta' => [],
            ],
            Response::HTTP_OK,
            [
                'Content-Type'                => 'application/json',
                'Access-Control-Allow-Origin' => '*',
            ]
        );
    }

    public function statistics()
    {
        $endpoints = Endpoint::with('bodies')->get();

        $statistics = [
            'systemCount' => $endpoints->count(),
            'bodyCount'   => $endpoints->reduce(
                static function (int $carry, Endpoint $current) {
                    return $carry + $current->bodies->count();
                },
                0
            ),
            'latestFetch' => $endpoints->pluck('fetched')->sort()->pop(),
            'vendors'     => $endpoints
                ->map(
                    static function (Endpoint $endpoint) {
                        if ([] === $endpoint->system || !array_key_exists('vendor', $endpoint->system)) {
                            return null;
                        }

                        return $endpoint->system['vendor'];
                    }
                )
                ->filter(
                    static function ($value) {
                        return is_string($value);
                    }
                )
                ->unique()
                ->sort()
                ->values(),
        ];

        return response()->json(
            [
                'data' => $statistics,
                'meta' => [],
            ],
            Response::HTTP_OK,
            [
                'Content-Type'                => 'application/json',
                'Access-Control-Allow-Origin' => '*',
            ]
        );
    }
}
