<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;

class AvailabilityPolicy
{
    public function view(User $user, Availability $availability): bool
    {
        return $user->doctor?->id === $availability->doctor_id;
    }

    public function update(User $user, Availability $availability): bool
    {
        return $user->doctor?->id === $availability->doctor_id;
    }

    public function delete(User $user, Availability $availability): bool
    {
        return $user->doctor?->id === $availability->doctor_id;
    }
}
