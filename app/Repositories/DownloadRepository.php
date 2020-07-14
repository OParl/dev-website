<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 23/11/2016
 * Time: 11:54.
 */

namespace App\Repositories;

use App\Model\DownloadVersion;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;

/**
 * Class DownloadRepository.
 */
abstract class DownloadRepository
{
    /** @var Collection */
    protected $versions = null;

    protected $fs = null;

    /**
     * @return string
     */
    abstract protected function getIdentifier();

    /**
     * DownloadRepository constructor.
     *
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;

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
     * @param string $version
     * @return DownloadVersion
     */
    public function getVersion(string $version)
    {
        return $this->versions->filter(function (DownloadVersion $downloadVersion) use ($version) {
            return $downloadVersion->isVersion($version);
        })->first();
    }

    /**
     * @return Collection of DownloadVersion
     */
    public function all()
    {
        return $this->versions;
    }
}
