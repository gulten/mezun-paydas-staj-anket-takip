<?php

use App\Firma;
use Illuminate\Database\Seeder;

class FirmalarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Firma::class, 10)->create();
    }
}
