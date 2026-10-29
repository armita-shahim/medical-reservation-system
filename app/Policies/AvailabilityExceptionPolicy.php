<?php

namespace App\Policies;

use App\Models\AvailabilityException;
use App\Models\User;

class AvailabilityExceptionPolicy
{
    public function view(User $user, AvailabilityException $exception): bool
    {
        return $user->doctor?->id === $exception->doctor_id;
    }

    public function update(User $user, AvailabilityException $exception): bool
    {
        return $user->doctor?->id === $exception->doctor_id;
    }

    public function delete(User $user, AvailabilityException $exception): bool
    {
        return $user->doctor?->id === $exception->doctor_id;
    }
}
