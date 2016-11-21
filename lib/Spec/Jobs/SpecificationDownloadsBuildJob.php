<?php
/**
 * Created by PhpStorm.
 * User: sgraupner
 * Date: 20/11/2016
 * Time: 14:48
 */

namespace OParl\Spec\Jobs;

use EFrane\HubSync\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Logging\Log;

class SpecificationDownloadsBuildJob
{
    use SynchronousProcess;

    public function handle(Filesystem $fs, Log $log)
    {
        $hubSync = new Repository($fs, 'oparl_spec', 'https://github.com/OParl/spec.git');

        if (!$hubSync->update()) {
            $log->error("Git pull failed");
        }

        $currentHead = $hubSync->getCurrentHead();

        $dockerCmd = sprintf(
            'docker run --rm -v $(pwd):/spec -w /spec oparl/specbuilder:latest make VERSION=%s clean archives',
            $currentHead
        );

        if (!$this->runSynchronousJob($hubSync->getAbsolutePath(), $dockerCmd)) {
            $log->error('Updating the downloadables failed');
        }

        $downloadsPath = 'downloads/' . $currentHead;

        if (!$fs->exists($downloadsPath)) {
            $fs->makeDirectory($downloadsPath);
        }

        $downloadableFormats = [
            'pdf',
            'html',
            'epub',
            'odt',
            'docx',
            'txt',
        ];

        collect($downloadableFormats)->map(function ($format) use ($currentHead) {
            return 'OParl-' . $currentHead . '.' .$format;
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->copy(
                $hubSync->getPath('out/' . $filename),
                $downloadsPath . '/' . $filename
            );
        });

        $downloadableArchives = [
            'zip',
            'tar.gz',
            'tar.bz2'
        ];

        collect($downloadableArchives)->map(function ($format) use ($currentHead) {
            return 'OParl-' . $currentHead . '.' .$format;
        })->map(function ($filename) use ($fs, $hubSync, $downloadsPath) {
            $fs->copy(
                $hubSync->getPath('archives/' . $filename),
                $downloadsPath . '/' . $filename
            );
        });
    }
}