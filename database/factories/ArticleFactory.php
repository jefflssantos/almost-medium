<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'body' => $faker->paragraph,
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        }
     ];
});
