<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveVersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'versions:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a compiled spec version.';

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
        //
    }
}
