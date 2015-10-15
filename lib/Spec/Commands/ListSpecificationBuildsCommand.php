<?php namespace OParl\Spec\Commands;

use OParl\Spec\BuildRepository;
use OParl\Spec\Model\SpecificationBuild;

class ListSpecificationBuildsCommand extends SpecificationCommand
{
    protected $name = 'specification:list';
    protected $description = 'List specification builds with details';

    public function handle(BuildRepository $repository)
    {
        $builds = $repository->getLatest(30, false)
      ->map(function (SpecificationBuild $build) {
      return [
        'id' => $build->id,
        'message' => $build->human_version,
        'avail' => ($build->is_available)  ? 'YES' : 'NO',
        'queried' => ($build->queried)     ? 'YES' : 'NO',
        'displayed' => ($build->displayed) ? 'YES' : 'NO'
      ];
    });

        $this->table(['ID', 'Version Text', 'Available', 'Queried', 'Displayed'], $builds);
    }
}
