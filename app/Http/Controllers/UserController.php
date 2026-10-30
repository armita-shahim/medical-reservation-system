<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Enums\AppointmentStatus;


class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->paginate(15);

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if ($user->role !== UserRole::Patient) {
            return response()->json([
                'message' => 'Only patients can be deleted from this endpoint'
            ], 403);
        }

        $hasAppointment = $user->appointments()->where('status', AppointmentStatus::Booked)
            ->where('start_at', '>', now())->exists();

        if ($hasAppointment) {
            return response()->json([
                'message' => 'This doctor has upcoming appointment, can not be deleted',
            ], 409);
        }


        DB::beginTransaction();

        try {
            $user->tokens()->delete();
            $user->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->noContent();
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('doctor');
        return new UserResource($user);
    }
}
