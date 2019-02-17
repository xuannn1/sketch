<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Chapter::class, function (Faker $faker) {
    return [
        'warning' => $faker->sentence,
        'annotation' => $faker->sentence,
    ];
});
