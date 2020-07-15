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
use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\Factory;

$slugify = Slugify::create();

/** @var Factory $factory */
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
