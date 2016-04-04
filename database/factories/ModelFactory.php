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
use RomanNumber\Formatter as Romanizer;

$slugify = Slugify::create();

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
    $name = $faker->words(7);

    return [
        'name'                => $name,
        'short_name'          => $faker->colorName,
        'website'             => $faker->url,
        'license'             => 'CC-BY-SA 3.0',
        'license_valid_since' => $faker->dateTimeBetween('-1 year'),
        'oparl_since'         => Carbon::createFromDate(2016, 1, 1),

        'ags' => $faker->numberBetween(100000, 169999999999),
        'rgs' => $faker->numberBetween(100000000000, 169999999999),

        'equivalent_body' => collect(
            range(0, $faker->numberBetween(0, 5)))
            ->map(function ($num) use ($faker) {
                return $faker->url;
            }),

        'contact_email' => $faker->email,
        'contact_name'  => $faker->name,

        'classification' => $faker->word,

        // TODO: sometimes substitute these with a Location object
        'street_address' => $faker->streetName,
        'postal_code'    => $faker->postcode,
        'locality'       => $faker->city
    ];
});

$factory->define(OParl\Server\Model\LegislativeTerm::class, function (Faker\Generator $faker) {
    $romanizer = new Romanizer();
    $startDate = new Carbon($faker->dateTimeThisCentury);

    return [
        'name' => sprintf('%d. Wahlperiode', $romanizer->formatNumber($faker->numberBetween(10, 22))),

        'start_date' => $startDate,
        'end_date'   => $startDate->addYear($faker->numberBetween(2, 5)),
    ];
});

$factory->define(OParl\Server\Model\AgendaItem::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(OParl\Server\Model\Consultation::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(OParl\Server\Model\File::class, function (Faker\Generator $faker) {
    // TODO: automatic file generation
    return [
    ];
});

$factory->define(OParl\Server\Model\Keyword::class, function (Faker\Generator $faker) use ($slugify) {
    $humanName = $faker->words(3);

    return [
        'human_name' => $humanName,
        'name'       => $slugify->slugify($humanName),
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

    return [
        'description' => $faker->words(7),
        'geometry'    => $geometry
    ];
});

$factory->define(OParl\Server\Model\Meeting::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(OParl\Server\Model\Membership::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(OParl\Server\Model\Organization::class, function (Faker\Generator $faker) {
    return [
        
    ];
});

$factory->define(OParl\Server\Model\Paper::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(),
        'reference' => $faker->word,
        'published_date' => $faker->dateTimeThisDecade,
        'paper_type' => $faker->word,
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
        'affix'           => '', // TODO: affix
        'gender'          => $genderString,
        'street_address'  => $faker->streetAddress,
        'postal_code'     => $faker->numberBetween(10000, 17000),
        'sub_locality'    => $faker->word,
        'locality'        => $faker->city,
        'life'            => $faker->text,
        'life_source'     => $faker->url,
    ];
});
