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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
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


$factory->define(App\Models\Tag::class, function (Faker\Generator $faker){
   return [
      'tagname' => str_random(3),
   ];
});

$factory->define(App\Models\TaggingThread::class, function (Faker\Generator $faker){
   return [
      'tag_id' => rand(1,10),
      'thread_id' =>rand(1,10),
   ];
});

$factory->define(App\Models\Thread::class, function (Faker\Generator $faker){
   return [
      'user_id' => function(){
         return factory('App\Models\User')->create()->id;
      },
      'title' => $faker->sentence,
      'brief' => $faker->sentence,
      'body' => $faker->paragraph,
   ];
});
$factory->define(App\Models\Post::class, function (Faker\Generator $faker){
   return [
      'user_id' => function(){
         return factory('App\Models\User')->create()->id;
      },
      'thread_id' => function(){
         return factory('App\Models\Thread')->create()->id;
      },
      'long_comment' => 1,
      'as_longcomment' => 1,
      'body' => $faker->paragraph,
   ];
});
$factory->define(App\Models\PostComment::class, function (Faker\Generator $faker){
   return [
      'user_id' => function(){
         return factory('App\Models\User')->create()->id;
      },
      'post_id' => function(){
         return factory('App\Models\Post')->create()->id;
      },
      'body' => $faker->sentence,
   ];
});

$factory->define(App\Models\LongComment::class, function (Faker\Generator $faker){
   return [
      'post_id' => function(){
         return factory('App\Models\Post')->create()->id;
      },
      'reviewed' => 1,
      'approved' => 1,
   ];
});

$factory->define(App\Models\Book::class, function (Faker\Generator $faker){
   return [
      'thread_id' => function() {
          return factory('App\Models\Thread')->create()->id;
      },
      'book_status' => 1,
      'book_length' => 1,
   ];
});
