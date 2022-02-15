<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Salon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        $salon = Salon::all();

        $roles = Role::all();

        User::all()->each(function ($user) use ($roles) {
            $user->roles()->attach(
                $roles->random()->id
            );
        });

        User::whereRelation('roles', 'id', '=', 2)->get()->each(function ($user) use ($salon) {
            $user->salons()->attach(
                $salon->random()->id
            );
        });
    }
}
