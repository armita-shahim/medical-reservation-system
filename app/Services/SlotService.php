<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Doctor;
use Carbon\Carbon;
use RRule\RRule;

class SlotService
{
    public function generate(Doctor $doctor, string $from, ?int $hospitalId = null): array
    {
        $timezone = 'Asia/Tehran';

        $rangeStart = Carbon::parse($from, $timezone)->startOfDay();
        $rangeEnd = $rangeStart->copy()->addDays(13)->endOfDay();

        // Get doctor's availabilities.
        $availabilities = $doctor->availabilities()
            ->whereDate('valid_from', '<=', $rangeEnd->toDateString())
            ->get();

        $filteredAvailabilities = collect();
        foreach ($availabilities as $availability) {
            if (
                $availability->valid_until === null ||
                $availability->valid_until->toDateString() >= $rangeStart->toDateString()
            ) {
                $filteredAvailabilities->push($availability);
            }
        }
        $availabilities = $filteredAvailabilities;

        if ($hospitalId !== null) {
            $availabilities = $availabilities->where('hospital_id', $hospitalId);
        }

        // Get exceptions and appointments only once.
        $exceptions = $doctor->availabilityExceptions()
            ->where('start_at', '<', $rangeEnd)
            ->where('end_at', '>', $rangeStart)
            ->get();

        $appointments = $doctor->appointments()
            ->where('status', '!=', AppointmentStatus::Cancelled->value)
            ->where('start_at', '<', $rangeEnd)
            ->where('end_at', '>', $rangeStart)
            ->get();


        $slots = collect();
        $now = Carbon::now($timezone);

        foreach ($availabilities as $availability) {
            //firstday and lastday
            $validFrom = Carbon::parse(
                $availability->valid_from,
                $timezone
            )->startOfDay();

            $firstDay = $rangeStart->copy();
            if ($validFrom->gt($rangeStart)) {
                $firstDay = $validFrom->copy();
            }

            $lastDay = $rangeEnd->copy();
            if ($availability->valid_until !== null) {
                $validUntil = Carbon::parse(
                    $availability->valid_until,
                    $timezone
                )->endOfDay();

                if ($validUntil->lt($rangeEnd))
                    $lastDay = $validUntil->copy();
            }

            if ($firstDay->gt($lastDay))
                continue;


            // Convert the RRULE string into options.
            $options = [];

            $parts = explode(';', $availability->rrule);
            foreach ($parts as $part) {
                [$key, $value] = explode('=', $part, 2);
                $options[$key] = $value;
            }

            // Start the recurrence from valid_from.
            $options['DTSTART'] = $validFrom->toDateString();

            $rule = new RRule($options);

            // Get working days.
            $days = $rule->getOccurrencesBetween(
                $firstDay->toDateString(),
                $lastDay->toDateString()
            );

            foreach ($days as $day) {
                $date = Carbon::parse($day, $timezone)->format('Y-m-d');

                $start = Carbon::parse(
                    $date . ' ' . $availability->start_time,
                    $timezone
                );

                $end = Carbon::parse(
                    $date . ' ' . $availability->end_time,
                    $timezone
                );

                $duration = (int) $availability->slot_duration_minutes;

                // Create slots.
                while ($start->copy()->addMinutes($duration)->lte($end)) {
                    $slotStart = $start->copy();
                    $slotEnd = $start->copy()->addMinutes($duration);

                    $start->addMinutes($duration);

                    // Skip past slots.
                    if ($slotStart->lt($now)) {
                        continue;
                    }

                    // Skip slots that overlap an exception.
                    $onLeave = false;
                    foreach ($exceptions as $exception) {
                        if ($slotStart->lt($exception->end_at) && $slotEnd->gt($exception->start_at)) {
                            $onLeave = true;
                            break;
                        }
                    }

                    if ($onLeave) {
                        continue;
                    }

                    // Skip slots that overlap an appointment.
                    $isBooked = false;
                    foreach ($appointments as $appointment) {
                        if ($slotStart->lt($appointment->end_at) && $slotEnd->gt($appointment->start_at)) {
                            $isBooked = true;
                            break;
                        }
                    }
                    if ($isBooked) {
                        continue;
                    }

                    $slots->push([
                        'hospital_id' => $availability->hospital_id,
                        'start_at' => $slotStart->toIso8601String(),
                        'end_at' => $slotEnd->toIso8601String(),
                    ]);
                }
            }
        }
        return $slots->sortBy('start_at')->values()->all;
    }
}
