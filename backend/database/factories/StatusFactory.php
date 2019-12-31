<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Status::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        'user_id' => function(){
            return \App\Models\User::inRandomOrder()->first()->id;
        },
    ];
});
