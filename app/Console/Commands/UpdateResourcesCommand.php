<?php

namespace App\Console\Commands;

use App\Jobs\ResourcesUpdateJob;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateResourcesCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oparl:update:resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the resources';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dispatch(new ResourcesUpdateJob());
        return 0;
    }
}