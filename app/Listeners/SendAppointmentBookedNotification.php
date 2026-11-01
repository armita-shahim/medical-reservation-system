<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Notifications\AppointmentBookedNotification;

class SendAppointmentBookedNotification
{
    public function handle(AppointmentBooked $event): void
    {
        $appointment = $event->appointment->load([
            'patient',
            'doctor.user',
        ]);

        $notification = new AppointmentBookedNotification($appointment);

        $appointment->patient?->notify($notification);
        $appointment->doctor?->user?->notify($notification);
    }
}
