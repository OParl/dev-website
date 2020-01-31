<?php
/**
 * @copyright 2017
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Exceptions;

class ValidationFailed extends \RuntimeException
{
    public static function validatorQuitUnexpectedly()
    {
        return new self('Validator quit unexpectedly.');
    }
}
