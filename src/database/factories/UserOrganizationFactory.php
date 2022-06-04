<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserOrganization>
 */
class UserOrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'organization_id' => $this->faker->numberBetween(0, 9),
            'user_id' => $this->faker->numberBetween(0, 9),
            'role' => $this->faker->randomElement(['org_admin', 'staff', 'chef']),
        ];
    }
}
