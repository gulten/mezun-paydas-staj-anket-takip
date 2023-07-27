<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Paydas;
use Faker\Generator as Faker;

$factory->define(Paydas::class, function (Faker $faker) {
    return [
        'telefon' => $faker->phoneNumber
    ];
});
