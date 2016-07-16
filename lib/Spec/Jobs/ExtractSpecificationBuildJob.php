<?php

namespace OParl\Spec\Jobs;

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
        // extract
        $extractCmd = sprintf('unzip %s', $this->build->tar_gz_storage_path);

        $process = new Process($extractCmd);
        $process->setWorkingDirectory($this->build->extracted_files_storage_path);
        $process->mustRun();

        try {
            $process->run();
        } catch (ProcessFailedException $e) {
            $output = $process->getOutput();
            \Log::error("Specification archive extraction failed. Full output follows:\n\n{$output}");
        }

        // move from out/* to ../
        $moveCmd = 'mv out/* ./ && rm -rf out/';

        $process = new Process($moveCmd);
        $process->setWorkingDirectory($this->build->extracted_files_storage_path);
        $process->mustRun();

        try {
            $process->run();
        } catch (ProcessFailedException $e) {
            $output = $process->getOutput();
            \Log::error("Moving specification assets failed. Full output follows:\n\n{$output}");
        }
    }
}
