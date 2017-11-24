<?php
/**
 * @copyright 2017
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace OParl\Spec\Exception;


class ValidationFailed extends \RuntimeException
{
    public static function validatorQuitUnexpectedly()
    {
        return new self("Validator quit unexpectedly.");
    }
}