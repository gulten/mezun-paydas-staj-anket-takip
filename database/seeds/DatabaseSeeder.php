<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(BolumBilgileriTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        //$this->call(BolumDersleriSeeder::class);
        $this->call(AnketlerTableSeeder::class);
        $this->call(BelgelerTableSeeder::class);
        $this->call(IslemTarihleriTableSeeder::class);
        //$this->call(FirmalarTableSeeder::class);
    }
}
