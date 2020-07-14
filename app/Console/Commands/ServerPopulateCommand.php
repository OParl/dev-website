<?php

namespace App\Console\Commands;

use App\Model\OParl10AgendaItem;
use App\Model\OParl10Body;
use App\Model\OParl10Consultation;
use App\Model\OParl10File;
use App\Model\OParl10Keyword;
use App\Model\OParl10LegislativeTerm;
use App\Model\OParl10Location;
use App\Model\OParl10Meeting;
use App\Model\OParl10Membership;
use App\Model\OParl10Organization;
use App\Model\OParl10Paper;
use App\Model\OParl10Person;
use App\Model\OParl10System;
use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class ServerPopulateCommand extends Command
{
    protected $signature = 'server:populate 
        {--refresh : Delete and regenerate all existing data (this includes running any missing db migrations)}';
    protected $description = '(Re-)populate the database with demo data.';

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @param Generator       $faker
     * @param DatabaseManager $databaseManager
     * @param Factory         $factory
     * @return int
     */
    public function handle(Generator $faker, DatabaseManager $databaseManager, Factory $factory)
    {
        $this->faker = $faker;
        $this->factory = $factory;

        $databaseManager->setDefaultConnection(config('database.demo_default'));
        $databaseManager->connection()->disableQueryLog();
        Model::unguard();

        if ($this->option('refresh')) {
            $this->call('server:reset');
        }

        $this->info('Populating the demoserver db...');

        $this->generateData();

        Model::reguard();
        $databaseManager->connection()->enableQueryLog();

        return 0;
    }

    protected function generateData(): void
    {
        $this->info('Creating a System');
        $system = $this->factory->of(OParl10System::class, 1)->create()->first();

        /** @var array<string, int> $amounts */
        $amounts = [
            'body'  => 3,
            'paper' => 100,
            'file'  => 200,
        ];

        // all following are defined per body
        /** @var array<string, array<int, int>> $amountsDynamic */
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

        collect($this->factory->of(OParl10Body::class, $amounts['body'])->create())->map(
            function (OParl10Body $body) use (
                $system,
                $amounts,
                $amountsDynamic
            ) {
                $this->line("\nCreating base data for Body ".$body->id."\n");

                $amounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);

                /* @var OParl10System $system */
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
            $orgas->each(function (OParl10Organization $orga) use ($body, $amounts) {
                $orga->people()->saveMany($body->people->random($amounts['member']));

                $orga->people->each(function ($person) use ($orga) {
                    /* @var OParl10Membership $membership */
                    $membership = $this->factory->of(OParl10Membership::class)->create();
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
        /* @var Collection $files */
        $files = $this->factory->of(OParl10File::class, $amounts['file'])->create();
        $files->each(
            function () use ($progressBar) {
                $progressBar->advance();
            }
        );
        $this->line('');

        /* Paper */
        $this->info('Creating Paper entities');
        $bodies = OParl10Body::all();
        $progressBar = new ProgressBar($this->output, $bodies->count() * $amounts['paper']);
        OParl10Body::all()->each(function (OParl10Body $body) use ($progressBar, $amounts) {
            $this->factory->of(OParl10Paper::class, $amounts['paper'])->create()->each(function ($paper) use ($body, $progressBar) {
                /* @var OParl10Paper $paper */
                $paper->mainFile()->associate(OParl10File::all()->random());
                $paper->body()->associate($body);
                $paper->save();

                $progressBar->advance();
            });
        });
        $this->line('');

        /* Meeting */
        foreach (OParl10Body::all() as $body) {
            $this->info('Creating Meeting entities for body '.$body->id);

            $meetingAmounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);
            $progressBar = new ProgressBar($this->output, $meetingAmounts['meeting']);

            $meetings = $this->factory->of(OParl10Meeting::class, $meetingAmounts['meeting'])->create();

            foreach ($meetings as $meeting) {
                /* @var OParl10Meeting $meeting */
                $meetingInnerAmounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);
                $body->organizations
                    ->random($meetingInnerAmounts['meeting.orgas'])
                    ->each(function (OParl10Organization $organization) use ($meeting) {
                        $meeting->organizations()->save($organization);
                    });

                if ($this->faker->boolean()) {
                    $meeting->location()->associate($this->getLocation());
                }

                $meetingAuxiliaryFiles = $files->random($this->faker->numberBetween(1, 5));

                if ($meetingAuxiliaryFiles->count() > 0) {
                    $meeting->auxiliaryFiles()->saveMany($meetingAuxiliaryFiles);
                }

                $meeting->save();

                $progressBar->advance();
            }

            $this->line('');
        }

        $this->info('Adding participants to Meetings');
        $progressBar = new ProgressBar($this->output, OParl10Meeting::count());
        foreach (OParl10Meeting::all() as $meeting) {
            $amounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);

            $meeting->organizations
                ->map(function ($orga) {
                    return $orga->people;
                })->flatten()
                ->random($amounts['meeting.participants'])
                ->each(function (OParl10Person $person) use ($meeting) {
                    $meeting->participants()->save($person);
                });

            $progressBar->advance();
        }

        $this->line('');

        $this->info('Adding agenda items to Meetings');
        $progressBar = new ProgressBar($this->output, OParl10Meeting::count());
        foreach (OParl10Meeting::all() as $meeting) {
            $amounts = $this->updateDynamicAmounts($amountsDynamic, $amounts);

            $meetingOrgas = $meeting->organizations;

            /* AgendaItem */
            $this->factory->of(OParl10AgendaItem::class, $amounts['meeting.items'])
                ->create()
                ->each(function (OParl10AgendaItem $item) use ($meeting, $meetingOrgas) {
                    /* @var OParl10Consultation $consultation */
                    $consultation = $this->factory->of(OParl10Consultation::class)->create();
                    $consultation->meeting()->associate($meeting);
                    $consultation->agendaItem()->associate($item);

                    $consultation->save();

                    $numberOfConsultationOrgas = $this->faker->numberBetween(1, $meetingOrgas->count());
                    $consultationOrgas = $meetingOrgas->random($numberOfConsultationOrgas);

                    $consultation->organizations()->saveMany($consultationOrgas);

                    $meeting->agendaItems()->save($item);
                });

            $progressBar->advance();
        }

        $this->line('');

        $this->info('Adding Papers to Consultations');
        $progressBar = new ProgressBar($this->output, OParl10Consultation::count());
        foreach (OParl10Consultation::all() as $consultation) {
            /** @var OParl10Paper $paper */
            $paper = OParl10Paper::all()->random();
            $paper->consultations()->save($consultation);

//            /* @var OParl10Consultation $consultation */
//            $consultation->paper()->associate();
//            $consultation->paper->body()->associate($consultation->organizations->first()->body);
//            $consultation->save();

            // explicit free()
            unset($consultation);

            $progressBar->advance();
        }

        $this->line('');
    }

    /**
     * @param array<string, array<int, int>> $amountsDynamic
     * @param array<string, int> $amounts
     *
     * @return array<string, int>
     */
    protected function updateDynamicAmounts(array $amountsDynamic, array $amounts): array
    {
        $amounts = collect($amountsDynamic)->map(function ($minmax) {
            [$min, $max] = $minmax;

            return $this->faker->numberBetween($min, $max);
        }
        )->merge($amounts)->toArray();

        return $amounts;
    }

    /**
     * @param int     $amount
     * @return Collection
     */
    protected function getSomeLegislativeTerms(int $amount): Collection
    {
        $progressBar = new ProgressBar($this->output, $amount);
        $this->info('Creating LegislativeTerm entities');

        /* @var $legislativeTerms Collection */
        $legislativeTerms = collect();

        $this->factory->of(OParl10LegislativeTerm::class, $amount)->create()->each(
            function (
                OParl10LegislativeTerm $term
            ) use ($legislativeTerms, $progressBar) {
                $legislativeTerms->push($term);
                $progressBar->advance();
            }
        );

        return $legislativeTerms;
    }

    /**
     * @param int $maxNb
     * @return OParl10Keyword[]|\Illuminate\Database\Eloquent\Collection|Collection
     */
    protected function getSomeKeywords($maxNb = 5): Collection
    {
        if ($maxNb < 0) {
            throw new \InvalidArgumentException('$maxNb must be greater than or equal to 0');
        }

        $amount = $this->faker->numberBetween(0, $maxNb);

        if ($amount == 0) {
            return collect();
        }

        $currentKeywordCount = OParl10Keyword::count();
        if ($currentKeywordCount < $amount) {
            $this->factory->of(OParl10Keyword::class, $amount)->create();
        }

        $keywordOrKeywords = OParl10Keyword::all()->random($amount);

        return ($keywordOrKeywords instanceof Collection) ? $keywordOrKeywords : collect([$keywordOrKeywords]);
    }

    /**
     * @return OParl10Location
     */
    protected function getLocation(): OParl10Location
    {
        // NOTE: Raising this value increases the spreading of different locations over all entities
        // NOTE: It also increases the total time needed for db population
        $willGenerateNewLocation = $this->faker->boolean(60);

        $locations = OParl10Location::all();
        if ($locations->count() == 0 || $willGenerateNewLocation) {
            $location = $this->factory->of(OParl10Location::class)->create();
        } else {
            $location = $locations->random();
        }

        return $location;
    }

    protected function getSomePeople(int $amount): Collection
    {
        $progressBar = new ProgressBar($this->output, $amount);
        $this->info('Creating People entities');

        /* @var $people Collection */
        $people = collect();

        // NOTE: it may be valuable to make it possible to fetch some existing people
        //       or only existing people with this method too
        $this->factory->of(OParl10Person::class, $amount)->create()->each(function (
            OParl10Person $person
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
        $this->factory->of(OParl10Organization::class, $amount)->create()->each(function (
            OParl10Organization $organization
        ) use ($organizations, $progressBar) {
            $organizations->push($organization);
            $progressBar->advance();
        });

        // TODO: suborganizations?
        return $organizations;
    }
}
