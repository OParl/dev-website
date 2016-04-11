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

// TODO: Maybe investigate why eloquent sometimes fails to synchronize the db correctly
if (!function_exists('hash_store')) {
    function hash_store(...$args)
    {
        static $hash_store = [];
        $argsString = var_export($args, true);

        if (!in_array($argsString, $hash_store)) {
            array_push($hash_store, md5($argsString));

            return true;
        } else {
            return false;
        }
    }
}

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(OParl\Server\Model\System::class, function (Faker\Generator $faker) {

    return [
        'name'          => 'OParl Demoserver',
        'oparl_version' => '1.0-dev',

        'contact_name'  => $faker->name,
        'contact_email' => $faker->email,
        'website'       => 'http://spec.oparl.org/demo'
    ];
});

$factory->define(OParl\Server\Model\Body::class, function (Faker\Generator $faker) {
    $name = ucfirst(implode(' ', $faker->words(7)));

    $equivalentBody = collect(
        range(0, $faker->numberBetween(0, 5)))
        ->map(function () use ($faker) {
            return $faker->url;
        })->toArray();

    return [
        'name'                => $name,
        'short_name'          => $faker->colorName,
        'website'             => $faker->url,
        'license'             => 'CC-BY-SA 3.0',
        'license_valid_since' => $faker->dateTimeBetween('-1 year'),
        'oparl_since'         => Carbon::createFromDate(2016, 1, 1),

        'ags' => $faker->numberBetween(100000, 169999999999),
        'rgs' => $faker->numberBetween(100000000000, 169999999999),

        'equivalent_body' => $equivalentBody,

        'contact_email' => $faker->email,
        'contact_name'  => $faker->name,

        'classification' => $faker->word,
    ];
});

$factory->define(OParl\Server\Model\LegislativeTerm::class, function (Faker\Generator $faker) {
    $romanizer = new Romanizer();

    $startDate = Carbon::instance($faker->dateTimeThisCentury);

    $startDate->hour = 0;
    $startDate->minute = 0;
    $startDate->second = 0;

    return [
        'name' => sprintf('%s. Wahlperiode', $romanizer->formatNumber($faker->numberBetween(10, 60))),

        'start_date' => $startDate,
        'end_date'   => Carbon::instance($startDate)->addYears($faker->numberBetween(1, 5))
    ];
});

$factory->define(OParl\Server\Model\AgendaItem::class, function (Faker\Generator $faker) {
    $number = $faker->randomNumber(4);

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
        'number' => $number,
        'name' => $faker->sentence(),
        'public' => $faker->boolean(),
        'result' => $faker->randomElement($results),
        'resolutionText' => $faker->realText($faker->numberBetween(200, 2000)),
        'start' => $start,
        'end' => Carbon::instance($start)->addMinutes($faker->randomElement(range(0, 60, 5)))
    ];
});

$factory->define(OParl\Server\Model\Consultation::class, function (Faker\Generator $faker) {
    $roles = ['Anhörung', 'Entscheidung', 'Kenntnisnahme', 'Vorberatung'];

    return [
        'authoritative' => $faker->boolean(),
        'role' => $faker->randomElement($roles),
    ];
});

$factory->define(OParl\Server\Model\File::class, function (Faker\Generator $faker) {
    // TODO: automatic file generation
    return [
    ];
});

$factory->define(OParl\Server\Model\Keyword::class, function (Faker\Generator $faker) use ($slugify) {
    do {
        $humanName = $faker->word;
    } while (Keyword::whereHumanName($humanName)->exists());

    return [
        'human_name' => $humanName,
    ];
});

$factory->define(OParl\Server\Model\Location::class, function (Faker\Generator $faker) {
    $geometry = json_encode([
        'type'        => 'point',
        'coordinates' => [
            $faker->latitude,
            $faker->longitude
        ]
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
        'name'         => $faker->word,
        'meetingState' => $meetingState,
        'cancelled'    => $faker->boolean(),
        'start'        => $startDate,
        'end'          => Carbon::instance($startDate)->addHours($faker->numberBetween(1, 5)),
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
    $shortName = 'O-' . $romanizer->formatNumber($faker->randomNumber(3));

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
        'Partei'
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
        'paper_type'     => $faker->word,
    ];
});

$factory->define(OParl\Server\Model\Person::class, function (Faker\Generator $faker) {
    $gender = $faker->boolean(30) ? 0 : 1;
    $genderString = ($gender) ? 'female' : 'male';

    if ($faker->numberBetween(0, 5) == 3) {
        $genderString = 'other';
    }

    return [
        'family_name'     => $faker->lastName,
        'given_name'      => ($gender) ? $faker->firstNameFemale : $faker->firstNameMale,
        'form_of_address' => '', // TODO: form of address
        'affix'           => ($gender) ? $faker->titleFemale : $faker->titleMale,
        'title'           => [],
        'gender'          => $genderString,
        'life'            => $faker->text,
        'life_source'     => $faker->url,
    ];
});
