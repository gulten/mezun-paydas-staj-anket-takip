<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Firma;
use Faker\Generator as Faker;

$factory->define(Firma::class, function (Faker $faker) {
    return [
        'adi' => $faker->company,
        'telefon' => '7(897) 979 97 97',
        'email' => $faker->unique()->companyEmail,
    ];
});
