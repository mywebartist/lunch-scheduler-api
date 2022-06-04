<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'resource_id' => $this->faker->numberBetween(1,10),
            'resource_type' => $this->faker->randomElement(['user', 'item','organization']),
            'media_type' => $this->faker->randomElement(['picture', 'video','avatar']),
            'filename' => 'default.jpg',
        ];
    }
}
