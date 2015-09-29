<?php namespace OParl\Spec\Jobs;

use OParl\Spec\Model\SpecificationBuild;

class ExtractSpecificationBuildJob extends SpecificationJob
{
  protected $build = null;

  /**
   * ExtractSpecificationBuildJob constructor.
   *
   * @param SpecificationBuild $build
   */
  public function __construct(SpecificationBuild $build)
  {
    $this->build = $build;
  }


  public function handle()
  {
    $extractCmd = sprintf('tar -xvzf %s %s',
      $this->build->tar_gz_storage_path,
      $this->build->extracted_files_storage_path
    );

    $returnValue = exec($extractCmd, $output);

    if ($returnValue !== 0)
    {
      $output = implode("\n", $output);
      \Log::error("Specification Build archive extraction failed. Full output follows:\n\n{$output}");
    }
  }
}