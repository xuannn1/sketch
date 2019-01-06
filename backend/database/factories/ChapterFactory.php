<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Chapter::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'brief' => $faker->paragraph,
        'annotation' => $faker->sentence,
    ];
});
