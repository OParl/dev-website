<?php

namespace OParl\Website\API\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class EndpointApiController
{
    /**
     * @SWG\Get(
     *     path="endpoints",
     *     tags={ "endpoints" },
     *     summary="list endpoints",
     *     @SWG\Response(
     *         response="200",
     *         description="A listing of known OParl endpoints",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Endpoint")
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
     */
    public function index(Request $request, Filesystem $fs)
    {
        $page = 0;
        $itemsPerPage = 25;

        if ($request->has('page')) {
            $page = intval($request->get('page'));
        }

        $endpoints = collect(Yaml::parse($fs->get('live/endpoints.yml')))
            ->sortBy('name')
            ->map(function ($endpoint) {
                return [
                    'url'         => $endpoint['url'],
                    'title'       => $endpoint['title'],
                    'description' => isset($endpoint['description']) ? $endpoint['description'] : null,
                ];
            })
            ->values();

        $data = $endpoints->forPage($page, $itemsPerPage);

        $pageCount = floor($endpoints->count() / $itemsPerPage);

        return response()->json([
            'data' => $data,
            'meta' => [
                'page'  => $page,
                'total' => $pageCount,
                'next'  => ($pageCount > $page) ? route('api.endpoints.index', ['page' => ++$page]) : null,
            ],
        ]);
    }
}
