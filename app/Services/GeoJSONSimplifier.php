<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Services;


class GeoJSONSimplifier
{
    public function simplify(array $geoJSON, float $epsilon)
    {
        $simplified = $geoJSON;

        if ('FeatureCollection' === $geoJSON['type']) {
            foreach ($geoJSON['features'] as $key => $feature) {
                $simplified['features'][$key] = $this->simplifyFeature($feature, $epsilon);
            }
        }

        if ('Feature' === $geoJSON['type']) {
            $simplified = $this->simplify($geoJSON, $epsilon);
        }

        return $simplified;
    }

    public function simplifyFeature(array $feature, float $epsilon): array
    {
        if (!$this->canSimplifyFeature($feature)) {
            return $feature;
        }

        $feature['geometry'] = $this->simplifyGeometry($feature['geometry'], $epsilon);

        return $feature;
    }

    /**
     * @param array $feature
     * @return bool
     */
    public function canSimplifyFeature(array $feature): bool
    {
        if (!array_key_exists('geometry', $feature)) {
            return false;
        } else {
            if (!array_key_exists('type', $feature['geometry'])) {
                return false;
            }
        }

        return in_array($feature['geometry']['type'], ['Line', 'Polygon']);
    }

    public function simplifyGeometry(array $geometry, float $epsilon): array
    {
        $geometry['coordinates'] = $this->rdp(
            $geometry['coordinates'],
            0,
            count($geometry['coordinates']) - 1,
            $epsilon
        );

        return $geometry;
    }

    protected function rdp(array $coordinates, int $startIndex, int $endIndex, float $epsilon): array
    {
        if ($startIndex == $endIndex) {
            return $coordinates;
        }

        $startPoint = $coordinates[$startIndex];
        $endPoint = $coordinates[$endIndex];

        $maxDistance = 0.0;
        $maxDistanceIndex = -1;

        for ($currentIndex = $startIndex + 1; $currentIndex < $endIndex; $currentIndex++) {
            $currentPoint = $coordinates[$currentIndex];

            $distance = $this->distanceToLine($currentPoint, $startPoint, $endPoint);

            if ($maxDistance < $distance) {
                $maxDistance = $distance;
                $maxDistanceIndex = $currentIndex;
            }
        }

        if ($maxDistance > $epsilon) {
            $leftResults = $this->rdp($coordinates, $startIndex, $maxDistanceIndex, $epsilon);
            $rightResults = $this->rdp($coordinates, $maxDistanceIndex + 1, $endIndex, $epsilon);

            $leftSlice = array_slice($leftResults, $startIndex, count($leftResults) - 1);
            $rightSlice = array_slice($rightResults, $maxDistanceIndex + 1, $endIndex);

            return array_merge($leftSlice, $rightSlice);
        } else {
            return array_slice($coordinates, $startIndex, $endIndex + 1);
        }
    }

    public function distanceToLine(array $measurePoint, array $lineStartPoint, array $lineEndPoint)
    {
        $a_x = $lineStartPoint[0];
        $a_y = $lineStartPoint[1];

        $b_x = $lineEndPoint[0];
        $b_y = $lineEndPoint[1];

        $p_x = $measurePoint[0];
        $p_y = $measurePoint[1];

        $A = $a_x + $b_x;

        $B = $a_y - $b_y;
        if (0 === $B) {
            $B = 1;
        }

        $C = 0;
        if ($a_y !== $b_y) {
            $C = (((($a_x - $b_x) / ($a_y - $b_y)) * $a_x) + $a_y) * ($a_y - $b_y);
        }

        return abs(($A * $p_x + $B * $p_y + $C)) / sqrt($A * $A + $B * $B);
    }
}
