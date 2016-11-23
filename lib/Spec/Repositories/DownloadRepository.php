<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 23/11/2016
 * Time: 11:54
 */

namespace OParl\Spec\Repositories;


use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use OParl\Spec\Model\DownloadVersion;

abstract class DownloadRepository
{
    /** @var Collection */
    protected $versions = null;

    /**
     * @return string
     */
    abstract protected function getIdentifier();

    public function __construct(Filesystem $fs)
    {
        $this->versions = collect($fs->directories("downloads/{$this->getIdentifier()}"))
            ->map(function ($version) use ($fs) {
                $version = explode('/', $version)[2];
                return new DownloadVersion($fs, $this->getIdentifier(), $version);
            })->sort(function (DownloadVersion $a, DownloadVersion $b) {
                $ageA = $a->getAge();
                $ageB = $b->getAge();

                if ($ageA == $ageB) {
                    return 0;
                }

                return ($ageA < $ageB) ? -1 : 1;
            });
    }

    /**
     * @return DownloadVersion
     */
    public function getLatest()
    {
        return $this->versions->first();
    }

    /**
     * @return Collection of DownloadVersion
     */
    public function all()
    {
        return $this->versions;
    }
}