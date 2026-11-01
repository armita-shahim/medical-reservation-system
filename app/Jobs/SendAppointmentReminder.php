<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Notifications\AppointmentReminderNotification;

class SendAppointmentReminder implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [60, 300];

    public int $appointmentId;

    public function __construct(int $appointmentId)
    {
        $this->appointmentId = $appointmentId;
    }

    public function handle(): void
    {
        $appointment = Appointment::find($this->appointmentId);

        if ($appointment === null) {
            return;
        }

        if (
            $appointment->status !== AppointmentStatus::Booked
            || $appointment->reminder_sent_at !== null
            || $appointment->start_at->lte(now())
            || $appointment->start_at->gt(now()->addHours(24))
        ) {
            return;
        }

        $appointment->load([
            'patient',
            'doctor.user',
        ]);

        if ($appointment->patient === null) {
            return;
        }

        $appointment->patient?->notify(
            new AppointmentReminderNotification($appointment)
        );

        $appointment->reminder_sent_at = now();
        $appointment->save();
    }
}
