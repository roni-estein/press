<?php

use Illuminate\Support\Str;
use RoniEstein\Press\Tag;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Tag::class, function (Faker\Generator $faker) {
    return [
        'text' => $faker->words(3,true),
        'slug' => function ($model) { return Str::slug($model['text']); },
    ];
});