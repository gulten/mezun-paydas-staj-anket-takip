<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\OgretimElemani;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(OgretimElemani::class, function (Faker $faker) {
    return [
        'unvan' => $faker->title,
        'cep_telefonu' => $faker->phoneNumber,
        'is_telefonu' => $faker->phoneNumber
    ];
});
