<?php namespace OParl\Spec;

use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;
use OParl\Spec\Jobs\RequestSpecificationBuildJob;
use OParl\Spec\Model\SpecificationBuild;

class BuildRepository
{
  protected $dispatcher;

  public function __construct(Dispatcher $dispatcher)
  {
    $this->dispatcher = $dispatcher;
  }

  public function getAvailable()
  {
    return SpecificationBuild::all()->filter(function ($build) {
      return $build->isAvailable;
    });
  }

  /**
   * @param int $amount
   * @return SpecificationBuild|\Illuminate\Support\Collection
   */
  public function getLatest($amount = 1)
  {
    if ($amount == 1)
    {
      return SpecificationBuild::orderBy('created_at', 'desc')->first();
    } else
    {
      return SpecificationBuild::orderBy('created_at', 'desc')->take($amount)->get();
    }
  }

  public function getDeletableByDate(Carbon $date)
  {
    return SpecificationBuild::all()->filter(function ($build) use ($date) {
      return $build->created_at < $date;
    });
  }

  public function getDeletableByAmount($amount = 30)
  {
    $amount = intval($amount);

    return SpecificationBuild::where('persistent', '=', false)
      ->orderBy('created_at', 'desc')->take($amount)->get();
  }

  public function getMissing()
  {
    return SpecificationBuild::all()->filter(function ($build) {
      return !$build->isAvailable;
    });
  }

  /**
   * @param $hash
   * @return SpecificationBuild
   */
  public function getWithHash($hash)
  {
    return SpecificationBuild::whereHash($hash)->first();
  }

  public function getWithTags(array $tags)
  {
    // TODO: implement tags for spec versions
  }

  public function fetchWithHash($hash)
  {
    $this->dispatcher->dispatch(new RequestSpecificationBuildJob($hash));
  }

  public function fetchMissing()
  {
    $this->getMissing()->each(function (SpecificationBuild $build) {
      $build->enqueue();
    })->first(function ($build) {
      $this->dispatcher->dispatch(new RequestSpecificationBuildJob($build->hash));
    });
  }

  public function diff($hashBase, $hashOther)
  {
    // TODO: implement build diffs
  }
}