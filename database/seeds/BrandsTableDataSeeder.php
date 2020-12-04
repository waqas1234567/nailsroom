<?php

use App\brand;
use Illuminate\Database\Seeder;

class BrandsTableDataSeeder extends Seeder
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
            brand::create([
                    'name'=>$faker->company,

                ]);
        endfor;
    }
}
