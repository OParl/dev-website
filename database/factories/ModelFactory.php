<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Model\Endpoint;
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
use Carbon\Carbon;
use Cocur\Slugify\Slugify;
use EFrane\BaseX\BaseX;
use Illuminate\Database\Eloquent\Factory;

$slugify = Slugify::create();

/* @var $factory Factory */
$factory->define(
    OParl10System::class,
    function (Faker\Generator $faker) {
        return [
            'name'          => 'OParl Demoserver',
            'oparl_version' => '1.0-dev',

            'contact_name'  => $faker->name,
            'contact_email' => $faker->email,
            'website'       => route('api.oparl.v1.index'),
        ];
    });

$factory->define(
    OParl10Body::class,
    function (Faker\Generator $faker) use ($slugify) {
        $names = [
            'Bezirksamt',
            'Kommunalverwaltung',
            'Rathaus',
        ];

        $name = $faker->randomElement($names).' '.($faker->optional() ? $faker->city : $faker->country);

        $equivalentBody = collect(
            range(0, $faker->numberBetween(0, 5))
        )
        ->map(function () use ($faker) {
            return $faker->url;
        })->toArray();

    $url = $faker->url;
    $contactName = $faker->name;

    return [
        'name'                => $name,
        'short_name'          => $faker->colorName,
        'website'             => $url,
        'license'             => 'https://creativecommons.org/licenses/by/4.0/',
        'license_valid_since' => $faker->dateTimeBetween('-1 year'),
        'oparl_since'         => Carbon::createFromDate(2016, 1, 1),

        'ags' => $faker->numberBetween(100000, 169999999999),
        'rgs' => $faker->numberBetween(100000000000, 169999999999),

        'equivalent_body' => $equivalentBody,

        'contact_email' => $slugify->slugify($contactName).'@'.parse_url($url, PHP_URL_HOST),
        'contact_name'  => $contactName,

        'classification' => $faker->word,
    ];
});

$factory->define(
    OParl10LegislativeTerm::class,
    function (Faker\Generator $faker) {
        $startDate = Carbon::instance($faker->dateTimeThisCentury);

        $startDate->hour = 0;
        $startDate->minute = 0;
        $startDate->second = 0;

        $data = [
            'name' => sprintf('%s. Wahlperiode', BaseX::toRoman($faker->numberBetween(10, 60))),

            'start_date' => $startDate,
        'end_date'   => Carbon::instance($startDate)->addYears($faker->numberBetween(1, 5)),
    ];

    if ($faker->boolean()) {
        $data['license'] = $faker->url;
    }

    return $data;
});

$factory->define(
    OParl10AgendaItem::class,
    function (Faker\Generator $faker) {
        $number = $faker->numberBetween(101, 999);

        if ($faker->boolean()) {
            $number = BaseX::toRoman($number);
        }

        $results = [
            'Vertagt',
            'Unverändert beschlossen',
            'Abgelehnt',
    ];

    $start = Carbon::instance($faker->dateTimeThisCentury);

    $start->hour = $faker->numberBetween(9, 17);
    $start->minute = $faker->randomElement([0, 15, 30, 45]);
    $start->second = 0;

    return [
        'number'          => $number,
        'name'            => $faker->sentence(),
        'public'          => $faker->boolean(),
        'result'          => $faker->randomElement($results),
        'resolution_text' => $faker->realText($faker->numberBetween(200, 2000)),
        'start'           => $start,
        'end'             => Carbon::instance($start)->addMinutes($faker->randomElement(range(0, 60, 5))),
    ];
});

$factory->define(
    OParl10Consultation::class,
    function (Faker\Generator $faker) {
        $roles = ['Anhörung', 'Entscheidung', 'Kenntnisnahme', 'Vorberatung'];

        return [
            'authoritative' => $faker->boolean(),
            'role'          => $faker->randomElement($roles),
        ];
    });

$factory->define(
    OParl10Keyword::class,
    function (Faker\Generator $faker) {
        $existingHumanNames = OParl10Keyword::pluck('human_name');

        do {
            $humanName = $faker->words($faker->numberBetween(1, 3), true);
        } while ($existingHumanNames->has($humanName));

        return [
            'human_name' => $humanName,
        ];
    });

$factory->define(
    OParl10Location::class,
    function (Faker\Generator $faker) {
        $geometry = json_encode(
            [
                'type'        => 'point',
                'coordinates' => [
                    $faker->latitude,
                    $faker->longitude,
                ],
            ]
        );

        $postalCode = sprintf('%05d', $faker->numberBetween(10000, 17000) - 1000);

        return [
        'description'    => $faker->sentence(),
        'geometry'       => $geometry,
        'street_address' => $faker->streetAddress,
        'postal_code'    => $postalCode,
        'sub_locality'   => $faker->word,
    ];
});

$factory->define(
    OParl10Meeting::class,
    function (Faker\Generator $faker) {
        $startDate = Carbon::instance($faker->dateTimeThisCentury);

        $startDate->hour = $faker->numberBetween(9, 17);
        $startDate->minute = $faker->randomElement([0, 15, 30, 45]);
        $startDate->second = 0;

        $meetingState = $faker->randomElement(['terminiert', 'eingeladen', 'durchgeführt']);

        return [
            'name'      => $faker->word,
        'meeting_state' => $meetingState,
        'cancelled'     => $faker->boolean(),
        'start'         => $startDate,
        'end'           => Carbon::instance($startDate)->addHours($faker->numberBetween(1, 5)),
    ];
});

$factory->define(
    OParl10Membership::class,
    function (Faker\Generator $faker) {
        $startDate = Carbon::instance($faker->dateTimeThisCentury);

        $startDate->hour = 0;
        $startDate->minute = 0;
        $startDate->second = 0;

        return [
            'role'         => $faker->colorName,
            'voting_right' => $faker->boolean(),
            'start_date'   => $startDate,
        'end_date'     => Carbon::instance($startDate)->addMonths($faker->numberBetween(5, 25)),
    ];
});

$factory->define(
    OParl10Organization::class,
    function (Faker\Generator $faker) {
        /** @var array<int, string> $words */
        $words = $faker->words($faker->numberBetween(3, 8));
        $name = ucfirst(implode(' ', $words));

        do {
            $shortNameNumber = $faker->numberBetween(100, 999);
        } while ($shortNameNumber < 0);

        $shortName = 'O-'.BaseX::toRoman($shortNameNumber);

        $organizationTypes = [
            'externes Gremium',
        'Fraktion',
        'Gremium',
        'Institution',
        'Partei',
        'Sonstiges',
        'Verwaltungsbereich',
    ];

    $classification = $faker->randomElement([
        'Parlament',
        'Ausschuss',
        'Beirat',
        'Projektbeirat',
        'Arbeitsgemeinschaft',
        'Verwaltungsrat',
        'Kommission',
        'Fraktion',
        'Partei',
    ]);

    $startDate = Carbon::instance($faker->dateTimeThisDecade);

    $startDate->hour = 0;
    $startDate->minute = 0;
    $startDate->second = 0;

    $externalBody = collect(
        range(0, $faker->numberBetween(0, 5)))
        ->map(function () use ($faker) {
            return $faker->url;
        })->toArray();

    return [
        'name'              => $name,
        'short_name'        => $shortName,
        'organization_type' => $faker->randomElement($organizationTypes),
        'classification'    => $classification,
        'start_date'        => $startDate,
        'end_date'          => Carbon::instance($startDate)->addMonths($faker->numberBetween(1, 36)),
        'website'           => $faker->url,
        'external_body'     => $externalBody,
    ];
});

$factory->define(
    OParl10Paper::class,
    function (Faker\Generator $faker) {
        return [
            'name'           => $faker->sentence,
            'reference'      => $faker->word,
            'published_date' => $faker->dateTimeThisDecade,
            'paper_type'     => $faker->randomElement(
                [
                    'Beschluss',
                    'Plan',
                    'Verordnung',
                    'Vorschlag',
                    'Eingabe',
            'Protokoll',
            'Kurzprotokoll',
        ]),
    ];
});

$factory->define(
    OParl10Person::class,
    function (Faker\Generator $faker) {
        $gender = $faker->boolean(30) ? 0 : 1;
        $genderString = ($gender) ? 'female' : 'male';

        if ($faker->numberBetween(0, 5) == 3) {
            $genderString = 'other';
        }

        $amountOfTitles = $faker->numberBetween(0, 3);
        if ($amountOfTitles == 0) {
            $titles = [];
    } else {
        $titles = range(1, $amountOfTitles);
    }

    $titles = collect($titles)->map(function () use ($gender, $faker) {
        return ($gender) ? $faker->titleFemale : $faker->titleMale;
    })->toArray();

    return [
        'family_name'     => $faker->lastName,
        'given_name'      => ($gender) ? $faker->firstNameFemale : $faker->firstNameMale,
        'form_of_address' => '', // TODO: form of address
        'affix'           => '',
        'title'           => $titles,
        'gender'          => $genderString,
        'life'            => $faker->text,
        'life_source'     => $faker->url,
    ];
});

$factory->define(
    OParl10File::class,
    function (Faker\Generator $faker) {
        $fileName = base_path('resources/documents/dummyfile.pdf');
        $sha1 = sha1_file($fileName);

        return [
            'storage_file_name' => $fileName,
            'mime_type'         => 'application/pdf',
            'sha1_checksum'     => $sha1,
            'text'              => $faker->realText(800),
            'file_name'         => $faker->randomAscii($faker->numberBetween(3, 12)),
            'name'              => $faker->randomAscii($faker->numberBetween(10, 20)),
    ];
});

$factory->define(
    Endpoint::class,
    function (Faker\Generator $faker) {
        return [
            'url' => $faker->url,
            'title' => $faker->sentence,
            'description' => $faker->text,
        ];
    }
);
