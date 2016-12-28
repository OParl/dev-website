<?php

namespace OParl\Server\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use OParl\Server\Model\Body;
use OParl\Server\Model\Keyword;
use OParl\Server\Model\LegislativeTerm;
use OParl\Server\Model\Location;
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
            $this->call('server:reset');
        }

        $this->info('Populating the demoserver db...');

        $this->generateData();

        Model::reguard();

        return 0;
    }

    protected function generateData()
    {
        $this->info('Creating a System');
        $system = factory(System::class, 1)->create();

        $amounts = [
            'body'            => $this->faker->numberBetween(3, 5),
            'paper'           => 250,

            // all following are defined per body

            'organisation'    => $this->faker->numberBetween(2, 10),
            'legislativeTerm' => $this->faker->numberBetween(1, 10),
            'meeting'         => $this->faker->numberBetween(10, 25),
            'people'          => $this->faker->numberBetween(20, 100),
            'member'          => $this->faker->numberBetween(5, 20),
        ];

        $this->info('Creating Paper entities');
        $progressBar = new ProgressBar($this->output, $amounts['paper']);
        collect(factory(Paper::class, $amounts['paper'])->create())->each(function ($paper) use ($progressBar) {
            $progressBar->advance();
        });
        $this->line('');

        collect(factory(Body::class, $amounts['body'])->create())->map(function ($body) use ($system, $amounts) {
            /* @var System $system */
            $system->bodies()->save($body);

            /* LegislativeTerm */
            $body->legislativeTerms()->saveMany($this->getSomeLegislativeTerms($amounts['legislativeTerm']));
            $this->line('');

            $body->keywords()->saveMany($this->getSomeKeywords(2));

            if ($this->faker->boolean()) {
                $body->location()->associate($this->getLocation());
            }

            /* People */
            $body->people()->saveMany($this->getSomePeople($amounts['people']));
            $this->line('');

            /* Organisation */
            $orgas = $this->getSomeOrganizations($amounts['organisation']);
            $orgas->each(function ($orga) use ($body, $amounts) {
                $orga->people()->saveMany($body->people->random($amounts['member']));
            });
            $body->organizations()->saveMany($orgas);

            $this->line('');
        });


//
//            $organizations = $this->getSomeOrganizations($this->faker->randomElement([1, 5, 10]));
//            $organizations->each(function (Organization $organization) use ($body, $people) {
//                $organization->body()->associate($body);
//
//                $people->random($this->faker->numberBetween(2, $people->count()))->each(function (
//                    Person $person
//                ) use (
//                    $organization
//                ) {
//                    /* @var $membership Membership */
//                    $membership = factory(Membership::class)->create();
//
//                    $membership->person()->associate($person);
//                    $membership->organization()->associate($organization);
//
//                    $membership->keywords()->saveMany($this->getSomeKeywords());
//
//                    $membership->save();
//                });
//
//                $organization->keywords()->saveMany($this->getSomeKeywords());
//                $organization->location()->associate($this->getLocation());
//
//                $organization->save();
//            });
//
//            $meetings = factory(Meeting::class, $this->faker->numberBetween(10, 50))->create();
//            $meetings->each(function (Meeting $meeting) use ($organizations) {
//                /* @var $organizations Collection */
//                if ($organizations->count() > 1) {
//                    $meetingOrganizations = $organizations->random($this->faker->numberBetween(1,
//                        $organizations->count() / 2));
//                } else {
//                    $meetingOrganizations = $organizations;
//                }
//
//                if ($meetingOrganizations instanceof Organization) {
//                    $meetingOrganizations = collect([$meetingOrganizations]);
//                }
//
//                $meeting->organizations()->saveMany($meetingOrganizations);
//                /* @var $possibleParticipants Collection */
//                $possibleParticipants = collect(
//                    $meetingOrganizations->map(function (Organization $organization) {
//                        return $organization->people;
//                    })->map(function (Collection $collection) {
//                        return $collection->all();
//                    })->first()
//                );
//
//                $participants = $possibleParticipants->random($this->faker->numberBetween(1,
//                    $possibleParticipants->count() / 2));
//                if ($participants instanceof Person) {
//                    $participants = collect([$participants]);
//                }
//
//                $meeting->participants()->saveMany($participants);
//
//                $location = null;
//                if ($meetingOrganizations->count() > 0) {
//                    $location = $meetingOrganizations->first()->location;
//                } else {
//                    if ($participants->count() > 0) {
//                        $location = $participants->first()->location;
//                    } else {
//                        $location = $this->getLocation();
//                    }
//                }
//
//                $meeting->location()->associate($location);
//
//                $agendaItems = factory(AgendaItem::class, $this->faker->numberBetween(1, 10))->create();
//                if ($agendaItems instanceof AgendaItem) {
//                    $agendaItems = collect([$agendaItems]);
//                }
//
//                $agendaItems->each(function (AgendaItem $agendaItem) use ($meeting) {
//                    $agendaItem->meeting()->associate($meeting);
//                });
//
//                // TODO: Invitation, Protocol, etc.
//            });
//        });
    }

    /**
     * @return Collection
     **/
    protected function getSomeLegislativeTerms($amount)
    {
        $progressBar = new ProgressBar($this->output, $amount);
        $this->info('Creating LegislativeTerm entities');

        /* @var $legislativeTerms Collection */
        $legislativeTerms = collect();

        $generatedLegislativeTermOrTerms = factory(LegislativeTerm::class, $amount)->create();
        if ($generatedLegislativeTermOrTerms instanceof Collection) {
            $generatedLegislativeTermOrTerms->each(function (
                LegislativeTerm $term
            ) use ($legislativeTerms, $progressBar) {
                $legislativeTerms->push($term);
                $progressBar->advance();
            });
        } else {
            $legislativeTerms->push($generatedLegislativeTermOrTerms);
            $progressBar->advance();
        }

        return $legislativeTerms;
    }

    protected function getSomeKeywords($maxNb = 5)
    {
        if ($maxNb < 0) {
            throw new \InvalidArgumentException('$maxNb must be greater than or equal to 0');
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

    protected function getSomePeople($amount)
    {
        $progressBar = new ProgressBar($this->output, $amount);
        $this->info('Creating People entities');

        /* @var $people Collection */
        $people = collect();

        // NOTE: it may be valuable to make it possible to fetch some existing people
        //       or only existing people with this method too
        factory(Person::class, $amount)->create()->each(function (
            Person $person
        ) use ($people, $progressBar) {
            $person->keywords()->saveMany($this->getSomeKeywords());

            if ($this->faker->boolean()) {
                $person->location()->associate($this->getLocation());
            }

            $people->push($person);

            $progressBar->advance();
        });

        $this->line('');

        return $people;
    }

    protected function getSomeOrganizations($maxNb = 2)
    {
        if ($maxNb < 1) {
            throw new \InvalidArgumentException('$maxNb must be greater than or equal to 1');
        }

        $amount = $this->faker->numberBetween(1, $maxNb);

        $this->info('Creating Organization entities');
        $progressBar = new ProgressBar($this->output, $amount);

        $organizations = collect();
        factory(Organization::class, $amount)->create()->each(function (
            Organization $organization
        ) use ($organizations, $progressBar) {
            $organizations->push($organization);
            $progressBar->advance();
        });

        $this->line('');

        // TODO: suborganizations?
        return $organizations;
    }
}
