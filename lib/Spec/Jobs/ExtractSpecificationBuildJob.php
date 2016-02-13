<?php namespace OParl\Spec\Jobs;

use OParl\Spec\Model\SpecificationBuild;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

        $process = new Process($extractCmd);
        $process->mustRun();

        try {
            $process->run();
        } catch (ProcessFailedException $e) {
            $output = $process->getOutput();
            \Log::error("Specification Build archive extraction failed. Full output follows:\n\n{$output}");
        }
    }
}
