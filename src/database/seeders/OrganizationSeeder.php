<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $org = new Organization();
        $org->name = 'coa gi';
        $org->description = 'coa descriptin';
        $org->website = 'www.coa.com';
        $org->save();

        Organization::factory()
            ->count(10)
            ->create();
    }
}
