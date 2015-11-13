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

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(App\Model\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Model\Post::class, function (Faker\Generator $faker) {
    $title = $faker->catchPhrase;

    $slugify = app(Slugify::class);

    return [
        'title' => $title,
        'slug' => $slugify->slugify($title),
        'content' => $faker->realText(600),
        'published_at' => $faker->dateTimeThisMonth
    ];
});
