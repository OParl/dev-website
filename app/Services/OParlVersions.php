<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace App\Services;


class OParlVersions
{
    protected $specification;

    public function __construct()
    {
        $this->specification = config('oparl.versions.specification');
    }

    public function getConstraintForVersion($module, $version)
    {
        return $this->getModule($module)[$version];
    }

    public function getVersionForConstraint($module, $constraint)
    {
        return array_flip($this->getModule($module))[$constraint];
    }

    public function getModule($module)
    {
        if (property_exists($this, $module)) {
            return $this->{$module};
        }

        return null;
    }
}
