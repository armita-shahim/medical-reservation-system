<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;


class AppointmentCancelled
{
    use Dispatchable, SerializesModels;

    public Appointment $appointment;
    public int $cancelledById;

    public function __construct(Appointment $appointment, int $cancelledById)
    {
        $this->appointment = $appointment;
        $this->cancelledById = $cancelledById;
    }
}
