<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Review::class, function (Faker $faker){
    return [
        'thread_id' => function(){
            return \App\Models\Thread::inRandomOrder()->first()->id;
        },
        'recommend' => (bool)rand(0,1),
        'long' => (bool)rand(0,1),
        'rating' => rand(0,10),
        'editor_recommend' => (bool)rand(0,1),
    ];
});
