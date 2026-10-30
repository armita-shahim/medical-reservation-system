<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Services\SlotService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function store(
        StoreAppointmentRequest $request,
        SlotService $slotService
    ) {
        $user = $request->user();

        if ($user->role !== UserRole::Patient) {
            return response()->json([
                'message' => 'Only patients can book appointments.',
            ], 403);
        }

        $data = $request->validated();

        DB::beginTransaction();

        try {
            $doctor = Doctor::where('id', $data['doctor_id'])
                ->lockForUpdate()
                ->first();

            if ($doctor === null) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Doctor not found.',
                ], 404);
            }

            $timezone = 'Asia/Tehran';

            $startAt = Carbon::parse(
                $data['start_at'],
                $timezone
            )->setTimezone($timezone);

            $slots = $slotService->generate(
                $doctor,
                $startAt->toDateString(),
                (int) $data['hospital_id']
            );

            $selectedSlot = null;

            foreach ($slots as $slot) {
                $slotStart = Carbon::parse($slot['start_at'])
                    ->setTimezone($timezone);

                if ($slotStart->equalTo($startAt)) {
                    $selectedSlot = $slot;
                    break;
                }
            }

            if ($selectedSlot === null) {
                DB::rollBack();

                return response()->json([
                    'message' => 'This time slot is not available.',
                ], 409);
            }

            $appointment = new Appointment([
                'patient_id' => $request->user()->id,
                'doctor_id' => $doctor->id,
                'hospital_id' => $data['hospital_id'],
                'start_at' => $selectedSlot['start_at'],
                'end_at' => $selectedSlot['end_at'],
                'notes' => $data['notes'] ?? null,
            ]);

            $appointment->status = AppointmentStatus::Booked;
            $appointment->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return (new AppointmentResource($appointment))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $appointments = Appointment::orderByDesc('start_at');

        if ($user->role === UserRole::Patient) {
            $appointments->where('patient_id', $user->id);
        } elseif ($user->role === UserRole::Doctor) {
            $doctor = $user->doctor;

            if ($doctor === null) {
                return response()->json([
                    'message' => 'Doctor profile not found.',
                ], 403);
            }

            $appointments->where('doctor_id', $doctor->id);
        } elseif ($user->role !== UserRole::Admin) {
            return response()->json([
                'message' => 'You are not allowed to view appointments.',

            ], 403);
        }

        return AppointmentResource::collection(
            $appointments->paginate(15)
        );
    }

    public function show(Request $request, Appointment $appointment)
    {
        $user = $request->user();

        if (!$this->canAccessAppointment($user, $appointment)) {
            return response()->json([
                'message' => 'You are not allowed to view this appointment.',
            ], 403);
        }

        return new AppointmentResource($appointment);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $user = $request->user();

        if (!$this->canAccessAppointment($user, $appointment)) {
            return response()->json([
                'message' => 'You are not allowed to cancel this appointment.',
            ], 403);
        }

        if ($appointment->status !== AppointmentStatus::Booked) {
            return response()->json([
                'message' => 'This appointment cannot be cancelled.',
            ], 409);
        }

        if ($appointment->start_at->isPast()) {
            return response()->json([
                'message' => 'A started or past appointment cannot be cancelled.',
            ], 409);
        }

        $data = $request->validate([
            'cancel_reason' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $appointment->status = AppointmentStatus::Cancelled;
        $appointment->cancelled_by_id = $user->id;
        $appointment->cancelled_at = now();
        $appointment->cancel_reason = $data['cancel_reason'] ?? null;

        $appointment->save();

        return new AppointmentResource($appointment);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = $request->user();

        $isDoctor = false;

        if ($user->role === UserRole::Doctor) {
            $doctor = $user->doctor;

            if ($doctor !== null && $appointment->doctor_id === $doctor->id) {
                $isDoctor = true;
            }
        }

        $isAdmin = $user->role === UserRole::Admin;

        if (!$isDoctor && !$isAdmin) {
            return response()->json([
                'message' => 'You are not allowed to change this appointment status.',
            ], 403);
        }

        $data = $request->validate([
            'status' => [
                'required',
                'string',
                'in:completed,no_show',
            ],
        ]);

        if ($appointment->status !== AppointmentStatus::Booked) {
            return response()->json([
                'message' => 'This appointment status cannot be changed.',
            ], 409);
        }

        if ($appointment->start_at->isFuture()) {
            return response()->json([
                'message' => 'A future appointment cannot be completed.',
            ], 409);
        }

        $appointment->status = AppointmentStatus::from($data['status']);
        $appointment->save();

        return new AppointmentResource($appointment);
    }

    private function canAccessAppointment($user, Appointment $appointment): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        if (
            $user->role === UserRole::Patient &&
            $appointment->patient_id === $user->id
        ) {
            return true;
        }

        if ($user->role === UserRole::Doctor) {
            $doctor = $user->doctor;

            if ($doctor !== null && $appointment->doctor_id === $doctor->id) {
                return true;
            }
        }

        return false;
    }
}
