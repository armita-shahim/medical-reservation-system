<?php

namespace App\Policies;

use App\Models\AvailabilityException;
use App\Models\User;

class AvailabilityExceptionPolicy
{
    public function delete(User $user, AvailabilityException $exception): bool
    {
        return $user->doctor?->id === $exception->doctor_id;
    }
}
