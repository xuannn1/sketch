<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Message::class, function (Faker $faker) {
    return [
      'poster_id' => function(){
          return \App\Models\User::inRandomOrder()->first()->id;
      },
      'receiver_id' => function(){
          return \App\Models\User::inRandomOrder()->first()->id;
      },
      'message_body_id' => function(){
          return \App\Models\MessageBody::inRandomOrder()->first()->id;
      },
      'seen' => 0,
    ];
});
