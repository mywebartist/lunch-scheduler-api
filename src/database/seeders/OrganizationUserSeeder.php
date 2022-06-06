<?php

namespace Database\Seeders;

use App\Models\OrganizationUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class OrganizationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $org = new OrganizationUser();
        $org->organization_id = 1;
        $org->user_id = 1;
//        $org->role = 'chef';
        $org->save();

        OrganizationUser::factory()
            ->count(10)
            ->create();



    }
}
