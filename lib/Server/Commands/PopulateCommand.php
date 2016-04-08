<?php

namespace OParl\Server\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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

        $bodies->each(function (Body $body) {
            // TODO: add people, organizations, memberships, meetings
            $people = $this->getSomePeople($this->faker->randomElement([10, 100, 1000]));
            $body->people()->saveMany($people);
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

        $legislativeTerms = $this->getSomeLegislativeTerms();
        $body->legislativeTerms()->saveMany($legislativeTerms);
        $body->keywords()->saveMany($this->getSomeKeywords(2));
        $body->location()->associate($this->getLocation());

        $body->save();

        return $body;
    }

    /**
     * @return Collection
     **/
    protected function getSomeLegislativeTerms($maxNb = 5)
    {
        if ($maxNb < 5) {
            throw new \InvalidArgumentException("\$maxNb must be greater than or equal to 5");
        }

        $amount = $this->faker->numberBetween(1, $maxNb);

        /* @var $legislativeTerms Collection */
        $legislativeTerms = collect();

        $generatedLegislativeTermOrTerms = factory(LegislativeTerm::class, $amount)->create();
        if ($generatedLegislativeTermOrTerms instanceof Collection) {
            $generatedLegislativeTermOrTerms->each(function (
                LegislativeTerm $term
            ) use ($legislativeTerms) {
                $legislativeTerms->push($term);
            });
        } else {
            $legislativeTerms->push($generatedLegislativeTermOrTerms);
        }

        return $legislativeTerms;
    }

    protected function getSomeKeywords($maxNb = 10)
    {
        if ($maxNb < 0) {
            throw new \InvalidArgumentException("\$maxNb must be greater than or equal to 0");
        }

        $amount = $this->faker->numberBetween(0, $maxNb);

        if ($amount == 0) return collect();

        $currentKeywordCount = Keyword::all()->count();
        if ($currentKeywordCount < $amount) {
            factory(Keyword::class, $amount - $currentKeywordCount)->create();
        }

        $keywordOrKeywords = Keyword::all()->random($amount);

        return ($keywordOrKeywords instanceof Collection) ? $keywordOrKeywords : collect([$keywordOrKeywords]);
    }

    protected function getSomePeople($maxNb = 50)
    {
        if ($maxNb < 2) {
            throw new \InvalidArgumentException("\$maxNb must be greater than or equal to 2");
        }

        $amount = $this->faker->numberBetween(2, $maxNb);

        /* @var $people Collection */
        $people = collect();

        // NOTE: it may be valuable to make it possible to fetch some existing people
        //       or only existing people with this method too
        factory(Person::class, $amount)->create()->each(function (
            Person $person
        ) use ($people) {
            $person->keywords()->saveMany($this->getSomeKeywords());
            $person->location()->associate($this->getLocation());

            $people->push($person);
        });

        return $people;
    }

    protected function getLocation() {
        // NOTE: Raising this value increases the spreading of different locations over all entities
        // NOTE: It also increases the total time needed for db population
        $willGenerateNewLocation = $this->faker->boolean(60);

        $locations = Location::all();
        if ($locations->count() == 0 || $willGenerateNewLocation) {
            $location = factory(Location::class)->create();
        } else {
            $location = $locations->random();
        }

        return $location;
    }
}