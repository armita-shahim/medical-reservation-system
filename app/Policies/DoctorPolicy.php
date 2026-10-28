<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;
use App\Enums\UserRole;

class DoctorPolicy
{

    public function update(User $user, Doctor $doctor): bool
    {
        return $user->role === UserRole::Admin || (
            $user->role === UserRole::Doctor
            && $doctor->user_id === $user->id
        );
    }
}
