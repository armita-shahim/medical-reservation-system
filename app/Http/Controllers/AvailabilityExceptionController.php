<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailabilityException\StoreAvailabilityExceptionRequest;
use App\Http\Requests\AvailabilityException\UpdateAvailabilityExceptionRequest;
use App\Http\Resources\AvailabilityExceptionResource;
use App\Models\AvailabilityException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;


class AvailabilityExceptionController extends Controller
{
    public function index()
    {
        $doctor = auth()->user->doctor;

        $exceptions = $doctor->availabilityExceptions()
            ->orderBy('start_at')
            ->paginate(15);

        return AvailabilityExceptionResource::collection($exceptions);
    }

    public function show(AvailabilityException $exception)
    {
        Gate::authorize('view', $exception);

        return new AvailabilityExceptionResource($exception);
    }

    public function store(StoreAvailabilityExceptionRequest $request)
    {
        $data = $request->validated();

        $exception = $request->user()
            ->doctor
            ->availabilityExceptions()
            ->create($data);

        return (new AvailabilityExceptionResource($exception))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        UpdateAvailabilityExceptionRequest $request,
        AvailabilityException $exception
    ) {
        Gate::authorize('update', $exception);

        $data = $request->validated();

        $start = Carbon::parse($data['start_at'] ?? $exception->start_at);
        $end = Carbon::parse($data['end_at'] ?? $exception->end_at);

        if ($end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'end_at' => 'End time must be after start time.',
            ]);
        }

        $exception->update($data);

        return new AvailabilityExceptionResource($exception->refresh());
    }

    public function destroy(AvailabilityException $exception)
    {
        Gate::authorize('delete', $exception);

        $exception->delete();

        return response()->noContent();
    }
}
