<?php namespace OParl\Spec\Commands;

use Illuminate\Console\Command;
use OParl\Spec\BuildRepository;

class DeleteSpecificationBuildsCommand extends Command
{
  protected $signature = 'specification:delete {--amount=}';

  protected $description = 'Delete a certain amount or date-frame of specification builds.';

  public function handle(BuildRepository $buildRepository)
  {
    $builds = null;


  }
}
