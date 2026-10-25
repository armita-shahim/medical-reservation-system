<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => UserRole::Doctor]),
            'specialty_id' => Specialty::factory(),
            'medical_council_code' => fake()->unique()->numerify('######'),
            'bio' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
