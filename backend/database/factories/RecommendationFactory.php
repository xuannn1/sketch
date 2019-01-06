<?php

use Faker\Generator as Faker;

$types = ['long','short','topic'];

$factory->define(App\Models\Recommendation::class, function (Faker $faker) use ($types){
    return [
        'thread_id' => function(){
            return \App\Models\Thread::inRandomOrder()->first()->id;
        },
        'brief' => $faker->sentence,
        'body' => $faker->paragraph,
        'type' => $types[array_rand($types)],
    ];
});
