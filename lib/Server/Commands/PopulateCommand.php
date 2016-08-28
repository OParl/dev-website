<?php

namespace OParl\Server\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use OParl\Server\Model\AgendaItem;
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
use Symfony\Component\Console\Helper\ProgressBar;

class PopulateCommand extends Command
{
    protected $signature = 'server:populate 
        {--refresh : Delete and regenerate all existing data (this includes running any missing db migrations)}';
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

        $this->generateData();

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

    protected function generateData()
    {
        $this->line('Creating the system entity.');
        $system = $this->generateSystem();

        $this->line('Creating some bodies');
        $bodies = collect(range(1, $this->faker->numberBetween(3, 9)))
            ->map(function () use ($system) {
                $body = $this->generateBodyWithLegislativeTerms($system);
                return $body;
            });

        $bodies->each(function (Body $body) {
            $people = $this->getSomePeople($this->faker->randomElement([10, 100, 1000]));
            $body->people()->saveMany($people);

            $organizations = $this->getSomeOrganizations($this->faker->randomElement([10, 20, 50]));
            $organizations->each(function (Organization $organization) use ($body, $people) {
                $organization->body()->associate($body);

                $people->random($this->faker->numberBetween(2, $people->count()))->each(function (
                    Person $person
                ) use (
                    $organization
                ) {
                    /* @var $membership Membership */
                    $membership = factory(Membership::class)->create();

                    $membership->person()->associate($person);
                    $membership->organization()->associate($organization);

                    $membership->keywords()->saveMany($this->getSomeKeywords());

                    $membership->save();
                });

                $organization->keywords()->saveMany($this->getSomeKeywords());
                $organization->location()->associate($this->getLocation());

                $organization->save();
            });

            $meetings = factory(Meeting::class, $this->faker->numberBetween(10, 100))->create();
            $meetings->each(function (Meeting $meeting) use ($organizations) {
                /* @var $organizations Collection */
                $meetingOrganizations = $organizations->random($this->faker->numberBetween(1,
                    $organizations->count() / 2));

                if ($meetingOrganizations instanceof Organization) {
                    $meetingOrganizations = collect([$meetingOrganizations]);
                }

                $meeting->organizations()->saveMany($meetingOrganizations);
                /* @var $possibleParticipants Collection */
                $possibleParticipants = collect(
                    $meetingOrganizations->map(function (Organization $organization) {
                        return $organization->people;
                    })->map(function (Collection $collection) {
                        return $collection->all();
                    })->first()
                );

                $participants = $possibleParticipants->random($this->faker->numberBetween(1,
                    $possibleParticipants->count() / 2));
                if ($participants instanceof Person) {
                    $participants = collect([$participants]);
                }

                $meeting->participants()->saveMany($participants);

                $location = null;
                if ($meetingOrganizations->count() > 0) {
                    $location = $meetingOrganizations->first()->location;
                } else {
                    if ($participants->count() > 0) {
                        $location = $participants->first()->location;
                    } else {
                        $location = $this->getLocation();
                    }
                }

                $meeting->location()->associate($location);

                $agendaItems = factory(AgendaItem::class, $this->faker->numberBetween(1, 25))->create();
                if ($agendaItems instanceof AgendaItem) {
                    $agendaItems = collect([$agendaItems]);
                }

                $agendaItems->each(function (AgendaItem $agendaItem) use ($meeting) {
                    $agendaItem->meeting()->associate($meeting);
                });

                // TODO: Invitation, Protocol, etc.

            });
        });
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

        // FIXME: keywords are so broken
        return collect();

        $amount = $this->faker->numberBetween(0, $maxNb);

        if ($amount == 0) {
            return collect();
        }

        $currentKeywordCount = Keyword::all()->count();
        if ($currentKeywordCount < $amount) {
            factory(Keyword::class, $amount - $currentKeywordCount)->create();
        }

        $keywordOrKeywords = Keyword::all()->random($amount);

        return ($keywordOrKeywords instanceof Collection) ? $keywordOrKeywords : collect([$keywordOrKeywords]);
    }

    protected function getLocation()
    {
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

    protected function getSomeOrganizations($maxNb = 2)
    {
        if ($maxNb < 2) {
            throw new \InvalidArgumentException("\$maxNb must be greater than or equal to 2");
        }

        $amount = $this->faker->numberBetween(2, $maxNb);

        // TODO: suborganizations?
        return factory(Organization::class, $amount)->create();
    }
}