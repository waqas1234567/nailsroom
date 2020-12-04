<?php

use App\collections;
use Illuminate\Database\Seeder;

class CollectionsTableDataSeeder extends Seeder
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
            collections::create([
                'name'=>$faker->company,
                 'brand_id'=>rand(1,100)

            ]);
        endfor;
    }
}
