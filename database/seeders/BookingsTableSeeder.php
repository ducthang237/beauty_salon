<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Salon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = User::whereRelation('roles', 'id', '=', 1)->get();
        $technical = User::whereRelation('roles', 'id', '=', 2)->get();
        $salon = Salon::all();

        Booking::factory(50)->make()->each(function ($booking) use ($customer, $technical, $salon) {
            $booking->salon_id = $salon->random()->id;
            $booking->customer_id = $customer->random()->id;
            $booking->technical_id = $technical->random()->id;
            $booking->save();
        });
    }
}
