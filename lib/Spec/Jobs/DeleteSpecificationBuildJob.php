<?php namespace OParl\Spec\Jobs;

use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\Model\SpecificationBuild;

class DeleteSpecificationBuildJob extends SpecificationJob
{
    /**
   * @var SpecificationBuild
   */
  protected $build = null;

  /**
   * DeleteSpecificationBuildJob constructor.
   *
   * @param SpecificationBuild $build
   */
  public function __construct(SpecificationBuild $build)
  {
      $this->build = $build;
  }

    public function handle(Filesystem $fs)
    {
        $fs->deleteDirectory($this->build->storage_path);
    }
}
