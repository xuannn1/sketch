<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Quote::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraph,
        'user_id' => function(){
            return \App\Models\User::inRandomOrder()->first()->id;
        },
    ];
});
