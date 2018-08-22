<?php

use Thinktomorrow\Chief\Users\User;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Menu\MenuItem;
use Illuminate\Support\Carbon;

$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'firstname' => $faker->firstName,
        'lastname'  => $faker->lastName,
        'email'     => $faker->email,
        'password'  => $password ?: bcrypt(str_random(8)),
    ];
});

$factory->define(Page::class, function (Faker\Generator $faker) {
    return [
        'collection'   => 'singles',
        'published_at' => $faker->dateTime(),
        'featured'     => $faker->boolean(),
        'publication'  => null,
        'title:nl'     => $faker->words(rand(2, 4), true),
        'title:en'     => $faker->words(rand(2, 4), true),
        'slug:nl'      => $faker->unique()->slug,
        'slug:en'      => $faker->unique()->slug,
    ];
});

$factory->define(MenuItem::class, function (Faker\Generator $faker) {
    return [
        'type'      => 'custom',
        'label:nl'  => 'nieuw label',
    ];
});
