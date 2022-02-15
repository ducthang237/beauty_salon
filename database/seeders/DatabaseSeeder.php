<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Role;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CitiesTableSeeder::class,
            ServicesTableSeeder::class,
            SalonsTableSeeder::class,
            RolesTableSeeder::class,
            // UsersTableSeeder::class,
            // BookingsTableSeeder::class
        ]);
    }
}
