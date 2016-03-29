<?php

namespace OParl\Server\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OParl\Server\Model\System;

class Populate extends Command
{
    protected $signature = 'server:populate {--refresh : Delete and regenerate all existing data (this includes running any missing db migrations)}';
    protected $description = '(Re-)populate the database with demo data.';

    public function handle()
    {
        $this->info('Populating the demoserver db...');

        $system = $this->generateSystem();


        return 0;
    }

    /**
     * @param FactoryBuilder $factory
     **/
    protected function generateSystem()
    {
        Model::unguard();

        if ($this->option('refresh') && System::all()->count() > 0) {
            System::truncate();
        }

        try {
            $system = System::findOrFail(1);
        } catch (ModelNotFoundException $e) {
            $system = factory(System::class)->create();
        }

        Model::reguard();

        return $system;
    }
}