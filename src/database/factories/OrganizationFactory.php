<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
//            'logo_media_id' => $this->faker->numberBetween(1,10),
            'name' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'website' => $this->faker->url(),
        ];
    }
}
