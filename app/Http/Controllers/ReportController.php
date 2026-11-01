<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Models\User;

class ReportController extends Controller
{
    public function summary()
    {
        $appointments = ['total' => Appointment::count()];

        foreach (AppointmentStatus::cases() as $status) {
            $appointments[$status->value] = Appointment::where('status', $status)->count();
        }

        return response()->json([
            'patients' => User::where('role', UserRole::Patient)->count(),
            'doctors' => Doctor::count(),
            'hospitals' => Hospital::count(),
            'specialties' => Specialty::count(),
            'appointments' => $appointments,
        ]);
    }

    public function topDoctors()
    {
        $doctors = Doctor::with(['user', 'specialty'])
            ->withCount('appointments')
            ->orderByDesc('appointments_count')
            ->limit(5)
            ->get();

        $data = [];

        foreach ($doctors as $doctor) {
            $data[] = [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'specialty' => $doctor->specialty->name,
                'appointments' => $doctor->appointments_count,
            ];
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function hospitals()
    {
        $hospitals = Hospital::withCount('appointments')
            ->orderByDesc('appointments_count')
            ->get();

        $data = [];

        foreach ($hospitals as $hospital) {
            $data[] = [
                'id' => $hospital->id,
                'name' => $hospital->name,
                'city' => $hospital->city,
                'appointments' => $hospital->appointments_count,
            ];
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
