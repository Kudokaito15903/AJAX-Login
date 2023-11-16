<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
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
        $faker = $this->faker;
        return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'picture' => null,
        'gender' => $faker->randomElement(['Male', 'Female']),
        'dob' => $faker->date(),
        'phone' => $faker->phoneNumber,
        'token' => null,
        'token_expire' => null,
        'password' => Hash::make('password'), // Replace 'password' with the desired default password
        'created_at' => now(),
        'updated_at' => now(),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
