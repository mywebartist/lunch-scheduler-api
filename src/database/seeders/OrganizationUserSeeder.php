<?php

namespace Database\Seeders;

use App\Models\OrganizationUser;
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

        $org_user = new OrganizationUser();
        $org_user->organization_id = 1;
        $org_user->user_id = 1;
        $org_user->roles = json_encode(['chef']);
        $org_user->save();

        $org_user = new OrganizationUser();
        $org_user->organization_id = 2;
        $org_user->user_id = 2;
        $org_user->roles = json_encode(['chef']);
        $org_user->save();

        $org_user = new OrganizationUser();
        $org_user->organization_id = 3;
        $org_user->user_id = 3;
        $org_user->roles = json_encode(['org_admin']);
        $org_user->save();

        OrganizationUser::factory()
            ->count(10)
            ->create();


    }
}
