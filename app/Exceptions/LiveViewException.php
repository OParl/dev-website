<?php
/**
 * @copyright 2020
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Exceptions;


use RuntimeException;

class LiveViewException extends RuntimeException
{
    public static function notLoadable(string $version)
    {
        return new self("LiveView for version {$version} is not loadable");
    }
}
