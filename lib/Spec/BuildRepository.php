<?php namespace OParl\Spec;

use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;
use OParl\Spec\Jobs\RequestSpecificationBuildJob;
use OParl\Spec\Model\SpecificationBuild;

/**
 * Class BuildRepository
 * @package OParl\Spec
 **/
class BuildRepository
{
    /**
     * @var Dispatcher
     **/
    protected $dispatcher;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return static
     **/
    public function getAvailable()
    {
        return SpecificationBuild::all()->filter(function ($build) {
            return $build->isAvailable;
        });
    }

    /**
     * @param SpecificationBuild $build
     * @return bool
     **/
    public function isLatest(SpecificationBuild $build)
    {
        return $build->created_at === $this->getLastModified();
    }

    /**
     * @return mixed
     **/
    public function getLastModified()
    {
        return $this->getLatest(1, false)->created_at;
    }

    /**
     * @param int $amount
     * @return SpecificationBuild|\Illuminate\Support\Collection
     */
    public function getLatest($amount = 1, $displayable = true)
    {
        /* @var $query \Illuminate\Database\Query\Builder */
        $query = SpecificationBuild::orderBy('created_at', 'desc');
        if ($displayable) {
            $query = $query->whereDisplayed(true);
        }

        if ($amount == 1) {
            return $query->first();
        } else {
            return $query->take($amount)->get();
        }
    }

    /**
     * @param Carbon $date
     * @return static
     **/
    public function getDeletableByDate(Carbon $date)
    {
        return SpecificationBuild::all()->filter(function ($build) use ($date) {
            return $build->created_at < $date;
        });
    }

    /**
     * @param int $amount
     * @return mixed
     **/
    public function getDeletableByAmount($amount = 30)
    {
        $amount = intval($amount);

        return SpecificationBuild::where('persistent', '=', false)
            ->orderBy('created_at', 'desc')->take($amount)->get();
    }

    /**
     * @param $hash
     * @return SpecificationBuild
     */
    public function getWithHash($hash)
    {
        return SpecificationBuild::whereHash($hash)->first();
    }

    /**
     * Request a build with a shortened hash.
     *
     * @param $short_hash string Shortened git hash
     * @return SpecificationBuild
     **/
    public function getWithShortHash($short_hash)
    {
        return SpecificationBuild::whereRaw("hash like '{$short_hash}%'")->first();
    }

    /**
     * Request builds by tags.
     *
     * @param array $tags
     * @return SpecificationBuild|\Illuminate\Support\Collection
     **/
    public function getWithTags(array $tags)
    {
        // TODO: implement tags for spec versions
    }

    /**
     * Request a specific job's assets from the build system
     *
     * @param $hash string The git hash of the to-be-requested build
     **/
    public function fetchWithHash($hash)
    {
        $this->dispatcher->dispatch(new RequestSpecificationBuildJob($hash));
    }

    /**
     * Enqueue all missing builds and start the request job on the first one.
     *
     * NOTE: It is not necessary to dequeue the first missing build again
     *       since the build job will dequeue the completed builds anyway.
     *
     * @return void
     */
    public function fetchMissing()
    {
        $this->getMissing()->each(function (SpecificationBuild $build) {
            $build->enqueue();
        })->first(function ($build) {
            $this->dispatcher->dispatch(new RequestSpecificationBuildJob($build->hash));
        });
    }

    /**
     * @return static
     **/
    public function getMissing()
    {
        return SpecificationBuild::all()->filter(function ($build) {
            return !$build->isAvailable;
        });
    }
}
