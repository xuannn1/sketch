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

$factory->define(App\Models\UserInfo::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
    	'jifen' => 50,
    	'shengfan' => 50,
    	'sangdian' => 50,
    	'xianyu' =>50,
    ];
});
