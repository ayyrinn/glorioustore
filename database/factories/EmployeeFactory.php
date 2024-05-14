<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'address' => fake()->address(),
            'gender' => fake()->randomElement(['M', 'F']),
            'role' => fake()->randomElement(['KASIR', 'KURIR']),
            'salary' => fake()->randomNumber(3, true),
            'DOB' => Carbon::now(),
        ];
    }
}
