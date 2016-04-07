<?php

namespace OParl\Server\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use OParl\Server\Model\Body;
use OParl\Server\Model\Consultation;
use OParl\Server\Model\File;
use OParl\Server\Model\Keyword;
use OParl\Server\Model\LegislativeTerm;
use OParl\Server\Model\Location;
use OParl\Server\Model\Meeting;
use OParl\Server\Model\Membership;
use OParl\Server\Model\Organization;
use OParl\Server\Model\Paper;
use OParl\Server\Model\Person;
use OParl\Server\Model\System;

class PopulateCommand extends Command
{
    protected $signature = 'server:populate {--refresh : Delete and regenerate all existing data (this includes running any missing db migrations)}';
    protected $description = '(Re-)populate the database with demo data.';

    /**
     * @var \Faker\Generator
     */
    protected $faker = null;

    public function handle(Generator $faker)
    {
        $this->faker = $faker;

        Model::unguard();

        if ($this->option('refresh')) {
            $this->info('Removing all existing demoserver data...');
            $this->truncate();
        }

        $this->info('Populating the demoserver db...');

        $system = $this->generateSystem();

        $bodies = collect(range(1, $this->faker->numberBetween(3, 9)))
            ->map(function () use ($system) {
                return $this->generateBodyWithLegislativeTerms($system);
            });

        Model::reguard();

        return 0;
    }

    protected function truncate()
    {
        System::truncate();
        Body::truncate();
        LegislativeTerm::truncate();
        Person::truncate();
        Organization::truncate();
        Membership::truncate();
        Meeting::truncate();
        Consultation::truncate();
        Paper::truncate();
        Location::truncate();
        File::truncate();
        Keyword::truncate();
    }

    protected function generateSystem()
    {
        return factory(System::class)->create();
    }

    protected function generateBodyWithLegislativeTerms(System $system)
    {
        /* @var $body Body */
        $body = factory(Body::class)->create();

        $body->system()->associate($system);
        $body->legislativeTerms()->saveMany(factory(LegislativeTerm::class,
            $this->faker->numberBetween(1, 5))->create());

        $keywords = $this->getSomeKeywords();

        $body->save();
    }

    protected function getSomeKeywords($maxNb = 10)
    {
        $amount = $this->faker->numberBetween(0, $maxNb);

        return factory(Keyword::class, $amount)->create();
    }
}