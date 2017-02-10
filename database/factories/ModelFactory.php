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
use Carbon\Carbon;
use Cocur\Slugify\Slugify;
use OParl\Server\Model\Keyword;
use RomanNumber\Formatter as Romanizer;

$slugify = Slugify::create();

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(OParl\Server\Model\System::class, function (Faker\Generator $faker) {
    return [
        'name'          => 'OParl Demoserver',
        'oparl_version' => '1.0-dev',

        'contact_name'  => $faker->name,
        'contact_email' => $faker->email,
        'website'       => route('api.index'),
    ];
});

$factory->define(OParl\Server\Model\Body::class, function (Faker\Generator $faker) use ($slugify) {
    $names = [
        'Bezirksamt',
        'Kommunalverwaltung',
        'Rathaus',
    ];

    $name = $faker->randomElement($names) . ' ' . ($faker->optional() ? $faker->city : $faker->country);

    $equivalentBody = collect(
        range(0, $faker->numberBetween(0, 5)))
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

        'contact_email' => $slugify->slugify($contactName) . '@' . parse_url($url, PHP_URL_HOST),
        'contact_name'  => $contactName,

        'classification' => $faker->word,
    ];
});

$factory->define(OParl\Server\Model\LegislativeTerm::class, function (Faker\Generator $faker) {
    $romanizer = new Romanizer();

    $startDate = Carbon::instance($faker->dateTimeThisCentury);

    $startDate->hour = 0;
    $startDate->minute = 0;
    $startDate->second = 0;

    $data = [
        'name' => sprintf('%s. Wahlperiode', $romanizer->formatNumber($faker->numberBetween(10, 60))),

        'start_date' => $startDate,
        'end_date'   => Carbon::instance($startDate)->addYears($faker->numberBetween(1, 5)),
    ];

    if ($faker->boolean()) {
        $data['license'] = $faker->url;
    }

    return $data;
});

$factory->define(OParl\Server\Model\AgendaItem::class, function (Faker\Generator $faker) {
    $number = $faker->numberBetween(101, 999);

    if ($faker->boolean()) {
        $romanizer = new Romanizer();
        $number = $romanizer->formatNumber($number);
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

$factory->define(OParl\Server\Model\Consultation::class, function (Faker\Generator $faker) {
    $roles = ['Anhörung', 'Entscheidung', 'Kenntnisnahme', 'Vorberatung'];

    return [
        'authoritative' => $faker->boolean(),
        'role'          => $faker->randomElement($roles),
    ];
});

$factory->define(OParl\Server\Model\Keyword::class, function (Faker\Generator $faker) use ($slugify) {
    $existingHumanNames = Keyword::all()->pluck('human_name');

    do {
        $humanName = $faker->words($faker->numberBetween(1, 3), true);
    } while ($existingHumanNames->has($humanName));

    return [
        'human_name' => $humanName,
    ];
});

$factory->define(OParl\Server\Model\Location::class, function (Faker\Generator $faker) {
    $geometry = json_encode([
        'type'        => 'point',
        'coordinates' => [
            $faker->latitude,
            $faker->longitude,
        ],
    ]);

    $postalCode = sprintf('%05d', $faker->numberBetween(10000, 17000) - 1000);

    return [
        'description'    => $faker->sentence(),
        'geometry'       => $geometry,
        'street_address' => $faker->streetAddress,
        'postal_code'    => $postalCode,
        'sub_locality'   => $faker->word,
    ];
});

$factory->define(OParl\Server\Model\Meeting::class, function (Faker\Generator $faker) {
    $startDate = Carbon::instance($faker->dateTimeThisCentury);

    $startDate->hour = $faker->numberBetween(9, 17);
    $startDate->minute = $faker->randomElement([0, 15, 30, 45]);
    $startDate->second = 0;

    $meetingState = $faker->randomElement(['terminiert', 'eingeladen', 'durchgeführt']);

    return [
        'name'          => $faker->word,
        'meeting_state' => $meetingState,
        'cancelled'     => $faker->boolean(),
        'start'         => $startDate,
        'end'           => Carbon::instance($startDate)->addHours($faker->numberBetween(1, 5)),
    ];
});

$factory->define(OParl\Server\Model\Membership::class, function (Faker\Generator $faker) {
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

$factory->define(OParl\Server\Model\Organization::class, function (Faker\Generator $faker) {
    $romanizer = new Romanizer();

    $name = ucfirst(implode(' ', $faker->words($faker->numberBetween(3, 8))));

    do {
        $shortNameNumber = $faker->numberBetween(100, 999);
    } while ($shortNameNumber < 0);

    $shortName = 'O-' . $romanizer->formatNumber($shortNameNumber);

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

$factory->define(OParl\Server\Model\Paper::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->sentence,
        'reference'      => $faker->word,
        'published_date' => $faker->dateTimeThisDecade,
        'paper_type'     => $faker->randomElement([
            'Beschluss',
            'Plan',
            'Verordnung',
            'Vorschlag',
            'Eingabe',
            'Protokoll',
            'Kurzprotokoll'
        ]),
    ];
});

$factory->define(OParl\Server\Model\Person::class, function (Faker\Generator $faker) {
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

$factory->define(OParl\Server\Model\File::class, function (Faker\Generator $faker) {
    $fileName = base_path('resources/documents/dummyfile.pdf');
    $sha1 = sha1_file($fileName);

    return [
        'storage_file_name' => $fileName,
        'mime_type' => 'application/pdf',
        'sha1_checksum' => $sha1,
        'text' => $faker->realText(800),
    ];
});
