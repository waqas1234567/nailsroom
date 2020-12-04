<?php

use App\store;
use Illuminate\Database\Seeder;

class storeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=0; $i<=5; $i++):
            store::create([
                'name' => $faker->name,
                'street' => $faker->streetAddress,
                'place' => $faker->address,
                'latitude'=>$faker->latitude,
                'longitude'=>$faker->longitude,
                'phone'=>$faker->phoneNumber,
                 'web_page'=>'ggogle.com'


            ]);
        endfor;
    }
}
