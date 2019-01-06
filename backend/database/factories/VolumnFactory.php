<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Volumn::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'brief' => $faker->paragraph,
    ];
});
