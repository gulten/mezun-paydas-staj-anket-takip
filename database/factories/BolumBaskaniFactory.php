<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BolumBaskani;
use App\Model;
use Faker\Generator as Faker;

$factory->define(BolumBaskani::class, function (Faker $faker) {
    return [
        'telefon' => $faker->phoneNumber,
        'email' => $faker->email
    ];
});
