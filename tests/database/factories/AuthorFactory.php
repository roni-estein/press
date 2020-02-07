<?php

use Illuminate\Support\Str;
use roniestein\Press\Author;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Author::class, function (Faker\Generator $faker) {
    return [
        
        'slug' => Str::slug($faker->name),
        'name' => $faker->sentence,
        'description' => $faker->sentence,
    ];
});