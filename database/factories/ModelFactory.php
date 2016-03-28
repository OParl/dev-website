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
use Cocur\Slugify\Slugify;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(OParl\Server\Model\System::class, function (Faker\Generator $faker) {

    return [
        'oparl_version' => '1.0-dev',
        'contact_name' => $faker->name,
        'contact_email' => $faker->email,
        'website' => 'http://spec.oparl.org/demo'
    ];
});

$factory->define(OParl\Server\Model\Body::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(OParl\Server\Model\LegislativeTerm::class, function (Faker\Generator $faker) {
    return [
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
    return [
    ];
});

$factory->define(OParl\Server\Model\Keyword::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(OParl\Server\Model\Location::class, function (Faker\Generator $faker) {
    return [
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
    ];
});

$factory->define(OParl\Server\Model\Person::class, function (Faker\Generator $faker) {
    return [
    ];
});
