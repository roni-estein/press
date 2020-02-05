<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use roniestein\Press\Post;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Post::class, function (Faker\Generator $faker) {
    return [
        'identifier' => Str::random(),
        'slug' => Str::slug($faker->sentence),
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'published_at' => function() use ($faker) {
            return ($faker->boolean ? Carbon::today()->addDays($faker->numberBetween(-2,2)): null);
        },
        'extra' => json_encode(['test' => 'value']),
    ];
});

$factory->state(Post::class, 'draft', function (\Faker\Generator $faker) {
    return [
        'published_at' => null,
    ];
});


$factory->state(Post::class, 'unpublished', function (\Faker\Generator $faker) {
    return [
        'published_at' => Carbon::today()->addDays(7),
    ];
});

$factory->state(Post::class, 'published', function (\Faker\Generator $faker) {
    return [
        'published_at' => Carbon::today()->subDays(7),
    ];
});