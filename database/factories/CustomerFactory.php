<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'custname' => $this->faker->name(),
            'custemail' => $this->faker->unique()->safeEmail,
            'custaddress' => $this->faker->address(),
            'custnum' => $this->faker->unique()->phoneNumber(),
            'custgender' => $this->faker->randomElement(['M', 'F']),
            'points' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
