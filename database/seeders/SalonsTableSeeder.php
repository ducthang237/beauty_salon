<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Salon;
use App\Models\City;

class SalonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = City::all();
        Salon::factory(5)->make()->each(function ($salon) use ($cities) {
            $salon->city_id = $cities->random()->id;
            $salon->save();
        });
    }
}
