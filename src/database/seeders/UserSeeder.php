<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        // add fixed user
        $user = new User();

        $user->name = 'k';
        $user->email = 'k@hotmail.com';
        $user->role = 'admin';
        $user->status = 1;
        $user->save();

        User::factory()
            ->count(9)
            ->create();
    }
}
