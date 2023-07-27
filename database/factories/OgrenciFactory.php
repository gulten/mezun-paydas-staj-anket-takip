<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Ogrenci;
use Faker\Generator as Faker;

$factory->define(Ogrenci::class, function (Faker $faker) {
    return [
        'ogrenci_no' => $faker->numberBetween(),
        'telefon' => $faker->phoneNumber,
        'kayit_yili' => $faker->date(),
        'adres' => $faker->address
    ];
});
