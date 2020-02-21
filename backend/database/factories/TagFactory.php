<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Tag::class, function (Faker $faker) {
    return [
        'tag_name' => str_random(6),
        'tag_explanation' => str_random(),
        'tag_type' => '大类',
        'is_bianyuan' => false,
        'is_primary' => false,
        'channel_id' => rand(1, 14),
        'parent_id' => 0
    ];
});
