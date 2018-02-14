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
            $this->warn('Created new environment file, please remember to configure it!');
        }

        $this->call('key:generate');
        $this->call('deploy');

        try {
            $mainDBFile = config('database.connections.sqlite.database');
            $this->info('Creating main database @ ' . $mainDBFile);
            touch($mainDBFile);
            $this->call('migrate');

            $demodataDBFile = config('database.connections.sqlite_demo.database');
            $this->info('Creating demodata database @ ' . $demodataDBFile);
            touch($demodataDBFile);
            $this->call('migrate', ['--database' => 'sqlite_demo']);
        } catch (\Exception $e) {
            $this->error('Errors occured while setting up the databases: ' . $e);
        }

        $this->call('server:populate');

        try {
            $this->call('oparl:init');
        } catch (\Exception $e) {
            $this->error('Errors occured while initializing the OParl components: ' . $e);
        }

        $this->call('up');
    }
}
