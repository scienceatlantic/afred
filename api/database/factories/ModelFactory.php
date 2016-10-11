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

$factory->define(App\Organization::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'isHidden' => 0
    ];
});

$factory->define(App\Province::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'isHidden' => 0
    ];
});

$factory->define(App\Sector::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'firstName' => $faker->firstName,
        'lastName' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'isActive' => 1,
        'dateLastLogin' => null,
        'dateLastPinged' => null,
        'dateCreated' => $faker->date,
        'dateUpdated' => $faker->date,
    ];
});
