<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OParl\Spec\Version;
use OParl\Spec\VersionRepository;
use Symfony\Component\Console\Input\InputOption;

class ListVersionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'versions:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all, available or extraneous specification versions.';

    protected function getOptions()
    {
      return [
        ['only', null, InputOption::VALUE_REQUIRED, 'Only show [available] or [extraneous] versions']
      ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(VersionRepository $versionRepository)
    {
      $versions = collect();
      $counts = [
        'extra' => 0,
        'avail' => 0,
        'total' => 0
      ];

      if (!$this->option('only') || $this->option('only') === 'extra')
      {
        $versions = $versions->merge($versionRepository->getExtraneous());
        $counts['extra'] = $versions->count();
      }

      if (!$this->option('only') || $this->option('only') === 'available')
      {
        $versions = $versions->merge($versionRepository->getAvailable());
        $counts['avail'] = $versions->count() - $counts['extra'];
      }

      $counts['total'] = $versions->count();

      $versions->each(function ($hash) {
        $this->line(' - ' . $hash);
      });

      $this->info("{$counts['total']} listed versions of which {$counts['extra']} are extraneous.");
    }
}
