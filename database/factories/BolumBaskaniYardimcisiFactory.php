<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BolumBaskaniYardimcisi;
use App\Model;
use Faker\Generator as Faker;

$factory->define(BolumBaskaniYardimcisi::class, function (Faker $faker) {
    return [
        'telefon' => $faker->phoneNumber
    ];
});
