<?php

namespace OParl\Server\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OParl\Server\Model\System;

class Populate extends Command
{
    protected $signature = 'server:populate {--refresh : Delete and regenerate all existing data (this includes running any missing db migrations)}';
    protected $description = '(Re-)populate the database with demo data.';

    public function handle(FactoryBuilder $factory)
    {
        $this->info('Populating the demoserver db...');

        $system = $this->generateSystem($factory);


        return 0;
    }

    /**
     * @param FactoryBuilder $factory
     **/
    protected function generateSystem(FactoryBuilder $factory)
    {
        Model::unguard();

        if ($this->option('refresh') && System::all()->count() > 0) {
            System::truncate();
        }

        try {
            $system = System::findOrFail(1);
        } catch (ModelNotFoundException $e) {
            $system = $factory->create(System::class);
        }

        Model::reguard();

        return $system;
    }
}