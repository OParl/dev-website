<?php

namespace OParl\Website\API\Controllers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class EndpointApiController
{
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
            });

        $data = $endpoints->forPage($page, $itemsPerPage);

        $pageCount = floor($endpoints->count() / $itemsPerPage);

        return response()->json([
            'data' => $data,
            'meta' => [
                'page' => $page,
                'total' => $pageCount,
                'next'  => ($pageCount > $page) ? route('api.endpoints.index', ['page' => ++$page]) : null
            ],
        ]);
    }
}
