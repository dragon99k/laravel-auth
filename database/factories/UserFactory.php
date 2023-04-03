<?php

namespace Database\Factories;

use App\Utils\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
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
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => Hash::make(config('mics.test_user.password')),
            'verification_code' => (string) Str::uuid(),
            'status' => \App\Enums\User\Status::Activated,
        ];
    }
}
