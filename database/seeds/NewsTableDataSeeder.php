<?php

use App\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class NewsTableDataSeeder extends Seeder
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
           News::create([
                    'title' => $faker->sentence,
                    'contents' => $faker->paragraph,
                    'newscategory_id' => 2,

                ]);
        endfor;

    }
}
