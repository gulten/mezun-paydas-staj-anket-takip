<?php


use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::truncate();

        User::create([
            'name' => 'Name',
            'email' => 'sss@mail.com',
            'password' => bcrypt('password')
        ]);



        factory(App\User::class, 3)
            ->create()
            ->each(function ($user) {
                factory(App\Ogrenci::class)->create(['user_id' => $user->id]);
            });

        /*DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('password'),
        ]);*/


        /*factory(App\User::class, 8)
            ->create()
            ->each(function ($user) {
                factory(App\OgretimElemani::class)->create(['user_id' => $user->id]);
            });
        */
        /*factory(App\User::class, 1)
            ->create()
            ->each(function ($user) {
                factory(App\BolumBaskani::class)->create(['user_id' => $user->id]);
            });

        factory(App\User::class, 3)
            ->create()
            ->each(function ($user) {
                factory(App\BolumBaskaniYardimcisi::class)->create(['user_id' => $user->id]);
            });

        factory(App\User::class, 20)
            ->create()
            ->each(function ($user) {
                factory(App\Ogrenci::class)->create(['user_id' => $user->id]);
            });

        factory(App\User::class, 20)
            ->create()
            ->each(function ($user) {
                factory(App\Mezun::class)->create(['user_id' => $user->id]);
            });

        factory(App\User::class, 10)
            ->create()
            ->each(function ($user) {
                factory(App\Paydas::class)->create(['user_id' => $user->id]);
            });*/
    }
}
