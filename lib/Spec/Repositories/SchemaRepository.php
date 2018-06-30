<?php
/**
 * @copyright 2018
 * @author Stefan "eFrane" Graupner <stefan.graupner@gmail.com>
 */

namespace OParl\Spec\Repositories;

use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\OParlVersions;


/**
 * Class SchemaRepository
 *
 * Operations on stored schema files
 *
 * @package OParl\Spec\Repositories
 */
class SchemaRepository
{
    /**
     * @var Filesystem
     */
    protected $fs;

    public function __construct(Filesystem $filesystem)
    {
        $this->fs = $filesystem;
    }

    public function all()
    {
        $oparlVersions = new OParlVersions();
        return collect($oparlVersions->getModule('specification'))->map(function ($_, $version) {
            return route('schema.list', compact('version'));
        })->all();
    }
}
