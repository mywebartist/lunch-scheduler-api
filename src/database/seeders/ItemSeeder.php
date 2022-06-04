<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $org = new Item();
        $org->organization_id = 1;
        $org->name = 'burgga';
        $org->description = 'burga desc';
        $org->save();


        Item::factory()
            ->count(10)
            ->create();
    }
}
