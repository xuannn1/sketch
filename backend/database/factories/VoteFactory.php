<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Vote::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\Models\User')->create()->id,
        'item_id' => function(){
            return \App\Models\Post::inRandomOrder()->first()->id;
        },
        'item_type' => config('constants.vote_info.item_types.post'),
        'attitude_type' => rand(1,4),
    ];
});
