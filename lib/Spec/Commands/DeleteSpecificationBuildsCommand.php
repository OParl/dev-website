<?php

namespace OParl\Spec\Commands;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use OParl\Spec\BuildRepository;
use OParl\Spec\Model\SpecificationBuild;
use Symfony\Component\Console\Input\InputArgument;

class DeleteSpecificationBuildsCommand extends SpecificationCommand
{
    protected $name = 'specification:delete';
    protected $description = 'Delete a certain amount or date-frame of specification builds.';

    public function handle(BuildRepository $buildRepository, Filesystem $fs)
    {
        if ($this->argument('object') == 'build') {
            $this->deleteBuilds($buildRepository, $fs);
        } else {
            $this->error('This command invocation is not implemented.');
        }
    }

    /**
     * Delete SpecificationBuild(s).
     *
     * @param BuildRepository $buildRepository
     * @param Filesystem $fs
     **/
    protected function deleteBuilds(BuildRepository $buildRepository, Filesystem $fs)
    {
        /* @var \Illuminate\Support\Collection $builds */
        $builds = null;

        $method = $this->argument('deletionMethod');

        if (is_numeric($method)) {
            $builds = $buildRepository->getDeletableByAmount($method);
        } else {
            try {
                $date = Carbon::createFromFormat('yyyy-mm-dd', $method);
            } catch (\Exception $e) {
                $this->error('Failed to parse date, please adhere to the format yyyy-mm-dd');
                return;
            }

            $builds = $buildRepository->getDeletableByDate($date);
        }

        $builds->each(function (SpecificationBuild $build) use ($fs) {
            if ($build->is_available) {
                $fs->deleteDirectory($build->storage_path);

                $build->is_available = false;
                $build->save();
            }
        });
    }

    protected function getArguments()
    {
        return [
            ['object', InputArgument::REQUIRED, 'The type of object to delete (currently only `build`)', null],
            [
                'deletionMethod',
                InputArgument::REQUIRED,
                'The method of deletion. (By date (format=yyyy-mm-dd) or amount.)',
                null,
            ],
        ];
    }
}
