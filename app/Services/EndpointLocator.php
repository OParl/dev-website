<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Cache\CacheManager;
use Illuminate\Http\Response;

class EndpointLocator
{
    /**
     * @var CacheManager
     */
    protected $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function lookup(int $openStreetMapRelationId): array
    {
        return $this->cacheManager->remember(
            'endpoint_location:'.$openStreetMapRelationId,
            3600,
            function () use ($openStreetMapRelationId) {
                $overPassQuery = "[out:json][timeout:25];rel({$openStreetMapRelationId});(._;>;);out;";
                $baseUrl = 'https://overpass-api.de/api/interpreter?data=';
                $url = $baseUrl.urlencode($overPassQuery);

                $client = new Client();
                $response = $client->get($url);

                if (Response::HTTP_OK !== $response->getStatusCode()) {
                    return '';
                }

                $responseString = $response->getBody()->getContents();

                return $this->toGeoJSON($responseString);
            }
        );
    }

    protected function toGeoJSON(string $responseString): array
    {
        $json = json_decode($responseString, true);

        $nodes = $this->getNodes($json);
        $ways = $this->getWays($json);
        $relations = $this->getRelations($json);

        if (1 !== count($relations)) {
            // TODO: throw error
        }

        $relation = array_pop($relations);

        $coordinates = [];

        foreach ($relation['members'] as $member) {
            if ('outer' === $member['role']) {
                $nodesForWay = $this->getNodesForWay($member['ref'], $ways, $nodes);

                foreach ($nodesForWay as $node) {
                    $coordinates[] = [$node['lat'], $node['lon']];
                }
            }

        }

        return ['type' => 'Feature', 'geometry' => ['type' => 'Polygon', 'coordinates' => $coordinates]];
    }

    /**
     * @param $json
     * @return array
     */
    protected function getNodes(array $json): array
    {
        return array_filter(
            $json['elements'],
            static function (array $element) {
                return 'node' === $element['type'];
            }
        );
    }

    /**
     * @param $json
     * @return array
     */
    protected function getWays(array $json): array
    {
        return array_filter(
            $json['elements'],
            static function (array $element) {
                return 'way' === $element['type'];
            }
        );
    }

    /**
     * @param $json
     * @return array
     */
    protected function getRelations(array $json): array
    {
        return array_filter(
            $json['elements'],
            static function (array $element) {
                return 'relation' === $element['type'];
            }
        );
    }

    protected function getNodesForWay(int $wayId, array $ways, array $nodes): array
    {
        $filteredWays = array_filter(
            $ways,
            static function ($way) use ($wayId) {
                return $wayId === $way['id'];
            }
        );

        $way = array_pop($filteredWays);

        $nodeIdsForWay = $way['nodes'];

        return array_filter(
            $nodes,
            static function ($node) use ($nodeIdsForWay) {
                return in_array($node['id'], $nodeIdsForWay);
            }
        );
    }
}
