<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemSelection>
 */
class ItemSelectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
//            'schedule_id' => $this->faker->numberBetween(1, 10),
            'organization_id' => $this->faker->numberBetween(1, 10),
//            'item_id' => $this->faker->numberBetween(1, 10),
            'items_ids' => json_encode([
                $this->faker->numberBetween(1, 10),
                $this->faker->numberBetween(1, 10),
                $this->faker->numberBetween(1, 10),
            ]),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+7 days')
        ];
    }
}
