<?php

namespace Database\Seeders;

use App\Models\ItemSelection;
use Illuminate\Database\Seeder;

class ItemSelectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemSelection::factory()
            ->count(10)
            ->create();
    }
}
