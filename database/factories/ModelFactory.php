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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'admin' => false,
        'activated' => true,
        'activation_token' => null,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Tag::class, function (Faker\Generator $faker){
   return [
      'tagname' => str_random(3),
   ];
});

$factory->define(App\TaggingThread::class, function (Faker\Generator $faker){
   return [
      'tag_id' => rand(1,10),
      'thread_id' =>rand(1,10),
   ];
});

$factory->define(App\Thread::class, function (Faker\Generator $faker){
   return [
      'user_id' => function(){
         return factory('App\User')->create()->id;
      },
      'title' => $faker->sentence,
      'brief' => $faker->sentence,
      'body' => $faker->paragraph,
   ];
});
$factory->define(App\Post::class, function (Faker\Generator $faker){
   return [
      'user_id' => function(){
         return factory('App\User')->create()->id;
      },
      'thread_id' => function(){
         return factory('App\Thread')->create()->id;
      },
      'body' => $faker->paragraph,
   ];
});
$factory->define(App\PostComment::class, function (Faker\Generator $faker){
   return [
      'user_id' => function(){
         return factory('App\User')->create()->id;
      },
      'post_id' => function(){
         return factory('App\Post')->create()->id;
      },
      'body' => $faker->sentence,
   ];
});
