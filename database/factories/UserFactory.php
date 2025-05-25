<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role_id' => fake()->numberBetween(2, 3),
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'phone_verified_at' => now(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'address' => fake()->address(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }


    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
            'phone_verified_at' => null,
        ]);
    }
}
