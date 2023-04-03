<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', config('mics.test_user.email'))->first();

        if (!$user) {
            \App\Models\User::updateOrCreate([
                'email' => config('mics.test_user.email'),
                'name' => config('mics.test_user.name'),
                'password' => Hash::make(config('mics.test_user.password')),
                'verification_code' => '',
                'status' => \App\Enums\User\Status::Activated,
            ]);
        }
    }
}
