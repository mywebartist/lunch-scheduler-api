<?php

namespace Database\Seeders;

use App\Models\User;
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

        $user_admin = User::whereEmail(env('ADMIN_EMAIL'))->first();
        if (!$user_admin) {
            // add fixed user
            $user_admin = new User();
            $user_admin->name = 'k';
            $user_admin->email = env('ADMIN_EMAIL');
            $user_admin->role = 'admin';
            $user_admin->status = 1;
            $user_admin->save();
        }

        $user_test = User::whereEmail(env('TEST_EMAIL'))->first();
        if (!$user_test) {
            // add fixed user
            $user_test = new User();
            $user_test->name = 'k';
            $user_test->email = env('TEST_EMAIL');
            $user_test->role = 'user';
            $user_test->status = 1;
            $user_test->save();
        }

        User::factory()
            ->count(9)
            ->create();
    }
}
