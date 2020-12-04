<?php

use Illuminate\Database\Seeder;

class AdminsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=0; $i<=100; $i++):
            DB::table('admins')
                ->insert([
                    'name'=>$faker->name,
                    'email' => $faker->email,
                    'password' => bcrypt('12345678')
                ]);
        endfor;
    }
}
