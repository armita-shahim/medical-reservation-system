<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->role === UserRole::Admin ||
            ($user->role === UserRole::Patient && $appointment->patient_id === $user->id) ||
            ($user->role === UserRole::Doctor && $user->doctor?->id === $appointment->doctor_id);
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $this->view($user, $appointment);
    }

    public function updateStatus(User $user, Appointment $appointment): bool
    {
        return $user->role === UserRole::Admin ||
            ($user->role === UserRole::Doctor && $user->doctor?->id === $appointment->doctor_id);
    }
}
