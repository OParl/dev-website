<?php

namespace OParl\Server\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use OParl\Server\Model\AgendaItem;
use OParl\Server\Model\Body;
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

        \DB::connection()->disableQueryLog();
        Model::unguard();

        if ($this->option('refresh')) {
            $this->call('server:reset');
        }

        $this->info('Populating the demoserver db...');

        $this->generateData();

        Model::reguard();
        \DB::connection()->enableQueryLog();

        return 0;
    }

    protected function generateData()
    {
        $this->info('Creating a System');
        $system = factory(System::class, 1)->create();

        $amounts = [
            'body'  => 3,
            'paper' => 250,
            'file'  => 200,
        ];

        // all following are defined per body
        $amountsDynamic = [
            'legislativeTerm' => [1, 10],
            'people'          => [20, 100],

            'organisation'         => [4, 12],
            'member'               => [5, 20],
            'meeting'              => [8, 15],
            'meeting.orgas'        => [1, 3],
            'meeting.items'        => [1, 10],
            'meeting.participants' => [1, 5],
        ];

        collect(factory(Body::class, $amounts['body'])->create())->map(function (Body $body) use (
            $system,
            $amounts,
            $amountsDynamic
        ) {
            $this->line("\nCreating base data for Body " . $body->id . "\n");

            $amounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);

            /* @var System $system */
            $system->bodies()->save($body);

            /* LegislativeTerm */
            $body->legislativeTerms()->saveMany($this->getSomeLegislativeTerms($amounts['legislativeTerm']));
            $this->line('');

            $body->keywords()->saveMany($this->getSomeKeywords(2));

            if ($this->faker->boolean()) {
                $body->location()->associate($this->getLocation());
            }

            /* Person */
            $body->people()->saveMany($this->getSomePeople($amounts['people']));
            $this->line('');

            /* Organisation + Membership */
            $orgas = $this->getSomeOrganizations($amounts['organisation']);
            $orgas->each(function (Organization $orga) use ($body, $amounts) {
                $orga->people()->saveMany($body->people->random($amounts['member']));

                $orga->people->each(function ($person) use ($orga) {
                    /* @var Membership $membership */
                    $membership = factory(Membership::class)->create();
                    $membership->organization()->associate($orga);
                    $membership->person()->associate($person);

                    $membership->save();
                });

                if ($this->faker->boolean()) {
                    $orga->location()->associate($this->getLocation());
                }

                if ($this->faker->boolean()) {
                    $orga->keywords()->saveMany($this->getSomeKeywords());
                }

                $orga->save();
            });

            $body->organizations()->saveMany($orgas);
            $body->save();
            $this->line('');

            $orgas = null;

            $this->line('');

            $body->save();
        });

        /* File */
        $this->info('Creating File entities');
        $progressBar = new ProgressBar($this->output, $amounts['file']);
        factory(File::class, $amounts['file'])->create()->each(function () use ($progressBar) {
            $progressBar->advance();
        });
        $this->line('');

        /* Paper */
        $this->info('Creating Paper entities');
        $progressBar = new ProgressBar($this->output, $amounts['paper']);
        factory(Paper::class, $amounts['paper'])->create()->each(function ($paper) use ($progressBar) {
            /* @var Paper $paper */
            $paper->mainFile()->associate(File::all()->random());

            $progressBar->advance();
        });
        $this->line('');

        /* Meeting */
        foreach (Body::all() as $body) {
            $this->info('Creating Meeting entities for body ' . $body->id);

            $meetingAmounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);
            $progressBar = new ProgressBar($this->output, $meetingAmounts['meeting']);

            $meetings = factory(Meeting::class, $meetingAmounts['meeting'])->create();

            foreach ($meetings as $meeting) {
                /* @var Meeting $meeting */
                $meetingInnerAmounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);
                $body->organizations
                    ->random($meetingInnerAmounts['meeting.orgas'])
                    ->each(function (Organization $organization) use ($meeting) {
                        $meeting->organizations()->save($organization);
                    });

                if ($this->faker->boolean()) {
                    $meeting->location()->associate($this->getLocation());
                }

                $progressBar->advance();
            };

            $this->line('');
        }

        $this->info('Adding participants to Meetings');
        $progressBar = new ProgressBar($this->output, Meeting::all()->count());
        foreach (Meeting::all() as $meeting) {
            $amounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);

            $meeting->organizations
                ->map(function ($orga) {
                    return $orga->people;
                })->flatten()
                ->random($amounts['meeting.participants'])
                ->each(function (Person $person) use ($meeting) {
                    $meeting->participants()->save($person);
                });

            $progressBar->advance();
        }

        $this->line('');

        $this->info('Adding AgendaItems to Meetings');
        $progressBar = new ProgressBar($this->output, Meeting::all()->count());
        foreach (Meeting::all() as $meeting) {
            $amounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);

            /* AgendaItem */
            factory(AgendaItem::class, $amounts['meeting.items'])
                ->create()
                ->each(function (AgendaItem $item) use ($meeting) {
                    $meeting->agendaItems()->save($item);
                });

            $progressBar->advance();
        }

        $this->line('');

        // TODO: add consultations to agenda items and papers to consultations
    }

    /**
     * @param $amountsDynamic
     * @param $amounts
     * @return array
     */
    protected function updateDynamicAmounts($amountsDynamic, $amounts)
    {
        $amounts = collect($amountsDynamic)->map(function ($minmax) {
            list($min, $max) = $minmax;

            return $this->faker->numberBetween($min, $max);
        })->merge($amounts)->toArray();

        return $amounts;
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

        $amount = $this->faker->numberBetween(0, $maxNb);

        if ($amount == 0) {
            return collect();
        }

        $currentKeywordCount = Keyword::all()->count();
        if ($currentKeywordCount < $amount) {
            factory(Keyword::class, $amount)->create();
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

        return $people;
    }

    protected function getSomeOrganizations($amount)
    {
        $this->info('Creating Organization entities');
        $progressBar = new ProgressBar($this->output, $amount);

        $organizations = collect();
        factory(Organization::class, $amount)->create()->each(function (
            Organization $organization
        ) use ($organizations, $progressBar) {
            $organizations->push($organization);
            $progressBar->advance();
        });

        // TODO: suborganizations?
        return $organizations;
    }
}
