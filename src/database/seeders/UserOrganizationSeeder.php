<?php

namespace Database\Seeders;

use App\Models\UserOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UserOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $org = new UserOrganization();
        $org->organization_id = 1;
        $org->user_id = 1;
//        $org->role = 'chef';
        $org->save();

        UserOrganization::factory()
            ->count(10)
            ->create();



    }
}
