<?php

namespace App\Console\Commands;

use EFrane\ConsoleAdditions\Command\Batch;
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

        try {
            foreach ([config('database.connections.sqlite.database'), config('database.connections.sqlite_demo.database')] as $databaseFile) {
                $this->info('Creating a database @ ' . $databaseFile);
                touch($databaseFile);
            }
        } catch (\Exception $e) {
            $this->error('Errors occured while setting up the databases: ' . $e);
        }

        try {
            Batch::create($this->getApplication(), $this->getOutput())
                ->add('key:generate')
                ->add('deploy')
                ->add('migrate')
                ->add('migrate --database=sqlite_demo')
                ->add('server:populate')
                ->run();
        } catch (\Exception $e) {
            $this->error('An error occured during primary application setup');
        }

        try {
            $this->call('oparl:init');
        } catch (\Exception $e) {
            $this->error('Errors occured while initializing the OParl components: ' . $e);
        }

        $this->call('up');
    }
}
