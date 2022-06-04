<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(ItemSeeder::class);
        $this->call(ItemSelectionSeeder::class);
        $this->call(MediaSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(ScheduleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call( UserOrganizationSeeder::class);
    }

}
