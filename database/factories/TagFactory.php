<?php

use Illuminate\Support\Str;
use roniestein\Press\Tag;

$factory->define(Tag::class, function (Faker\Generator $faker) {
    return [
        'text' => $faker->word,
        'slug' => function ($model) { return Str::slug($model['text']); },
    ];
});