<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Http\Resources\DoctorResource;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialty'])
            ->orderBy('id')
            ->paginate(15);

        return DoctorResource::collection($doctors);
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specialty']);

        return new DoctorResource($doctor);
    }

    public function store(StoreDoctorRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->role = UserRole::Doctor;
            $user->save();

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialty_id' => $data['specialty_id'],
                'medical_council_code' => $data['medical_council_code'],
                'bio' => $data['bio'] ?? null,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $doctor->load(['user', 'specialty']);

        return (new DoctorResource($doctor))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        Gate::authorize('update', $doctor);

        $doctor->update($request->validated());

        $doctor->load(['user', 'specialty']);

        return new DoctorResource($doctor);
    }

    public function destroy(Doctor $doctor)
    {
        DB::beginTransaction();

        try {
            $user = $doctor->user;
            $user->tokens()->delete();
            $doctor->delete();
            $user->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->noContent();
    }
}
