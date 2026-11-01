<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Enums\AppointmentStatus;
use App\Jobs\SendAppointmentReminder;
use App\Models\Appointment;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {

    // Booked appointments in next 24 hours not reminded yet.
    $ids = Appointment::where('status', AppointmentStatus::Booked->value)
        ->whereNull('reminder_sent_at')
        ->where('start_at', '>', now())
        ->where('start_at', '<=', now()->addHours(24))
        ->pluck('id');

    foreach ($ids as $id) {
        SendAppointmentReminder::dispatch($id);
    }
})->name('send-appointment-reminders')->everyFifteenMinutes()->withoutOverlapping();
