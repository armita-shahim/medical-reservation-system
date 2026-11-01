<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelledNotification extends Notification
{
    use Queueable;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Cancelled')
            ->greeting('Hello ' . $notifiable->name)
            ->line('An appointment has been cancelled.')
            ->line('Doctor: ' . $this->appointment->doctor->user->name)
            ->line(
                'Date: ' . $this->appointment->start_at
                    ->copy()
                    ->timezone('Asia/Tehran')
                    ->format('Y-m-d H:i')
            );
    }

    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'start_at' => $this->appointment->start_at
                ->copy()
                ->timezone('Asia/Tehran')
                ->toIso8601String(),
            'doctor_name' => $this->appointment->doctor->user->name,
            'message' => 'An appointment has been cancelled.',
        ];
    }
}
