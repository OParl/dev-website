<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    protected $signature = 'setup';
    protected $description = 'Runs all commands necessary for initial setup of the application.';

    public function handle()
    {
        $this->info('Setting up the application...');

        $this->call('down');

        if (!file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }

        touch(storage_path('database.sqlite'));

        $this->call('migrate');

        $this->call('oparl:update:specification');

        $this->call('optimize');

        $this->call('up');
    }
}
