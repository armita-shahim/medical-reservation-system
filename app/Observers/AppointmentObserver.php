<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use Illuminate\Support\Facades\Auth;


class AppointmentObserver
{
    public function created(Appointment $appointment): void
    {
        AppointmentStatusHistory::create([
            'appointment_id' => $appointment->id,
            'from_status' => null,
            'to_status' => $appointment->status,
            'changed_by_id' => Auth::id()
        ]);
    }

    public function updated(Appointment $appointment): void
    {
        if (!$appointment->wasChanged('status')) {
            return;
        }
        AppointmentStatusHistory::create([
            'appointment_id' => $appointment->id,
            'from_status' => $appointment->getOriginal('status'),
            'to_status' => $appointment->status,
            'changed_by_id' => Auth::id()
        ]);
    }
}
