<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use App\Notifications\AppointmentCancelledNotification;

class SendAppointmentCancelledNotification
{
    public function handle(AppointmentCancelled $event): void
    {
        $appointment = $event->appointment->load([
            'patient',
            'doctor.user',
        ]);

        $notification = new AppointmentCancelledNotification($appointment);

        $patient = $appointment->patient;
        $doctorUser = $appointment->doctor?->user;

        if ($patient !== null && $patient->id !== $event->cancelledById) {
            $patient->notify($notification);
        }

        if ($doctorUser !== null && $doctorUser->id !== $event->cancelledById) {
            $doctorUser->notify($notification);
        }
    }
}
