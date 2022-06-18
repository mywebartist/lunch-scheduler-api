<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'organization_id' => $this->faker->numberBetween(1, 9),
//            'thumbnail_media_id' => $this->faker->numberBetween(0,10),
            'name' => $this->faker->randomElement(['burger', 'pizza', 'salad']),
            'description' => $this->faker->paragraph(),
        ];
    }
}
