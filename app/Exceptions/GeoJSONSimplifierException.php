<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Exceptions;


use RuntimeException;

class GeoJSONSimplifierException extends RuntimeException
{
    public static function unsupportedFeatureType($type): self
    {
        return new self('Unsupported Feature Type: '.$type);
    }
}
