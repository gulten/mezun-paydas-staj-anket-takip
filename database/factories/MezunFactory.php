<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Mezun;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Mezun::class, function (Faker $faker) {
    return [
        'telefon' => $faker->phoneNumber,
        'mezuniyet_tarihi' => $faker->date(),
        'mezuniyet_suresi' => $faker->numberBetween(36,52)
    ];
});
