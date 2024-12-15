<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'document'  => $this->faker->unique()->numerify('###########'),
            'email'     => $this->faker->unique()->safeEmail,
            'password'  => 'secret',
            'user_type' => $this->faker->randomElement(['common', 'merchant']),
        ];
    }
}
