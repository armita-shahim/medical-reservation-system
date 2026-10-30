<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Http\Resources\AvailabilityResource;
use App\Http\Requests\Availability\AvailabilityRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AvailabilityController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;

        $availabilities = $doctor->availabilities()
            ->orderBy('id')
            ->paginate(15);

        return AvailabilityResource::collection($availabilities);
    }

    public function show(Availability $availability)
    {
        Gate::authorize('view', $availability);

        return new AvailabilityResource($availability);
    }

    public function store(AvailabilityRequest $request)
    {
        $data = $request->validated();

        $days = implode(',', $data['days']);
        $interval = $data['interval'] ?? 1;

        $data['rrule'] = "FREQ=WEEKLY;INTERVAL=$interval;BYDAY=$days;WKST=SA";

        unset($data['days'], $data['interval']);

        $doctor = $request->user()->doctor;

        $availability = $doctor->availabilities()->create($data);

        return (new AvailabilityResource($availability))
            ->response()
            ->setStatusCode(201);
    }

    public function update(AvailabilityRequest $request, Availability $availability)
    {
        Gate::authorize('update', $availability);

        $data = $request->validated();

        $start = substr($data['start_time'] ?? $availability->start_time, 0, 5);
        $end = substr($data['end_time'] ?? $availability->end_time, 0, 5);

        if ($end <= $start) {
            throw ValidationException::withMessages([
                'end_time' => 'End time must be after start time.',
            ]);
        }

        if (isset($data['days'])) {
            $days = implode(',', $data['days']);
            $interval = $data['interval'] ?? 1;

            $data['rrule'] = "FREQ=WEEKLY;INTERVAL=$interval;BYDAY=$days;WKST=SA";
        }

        unset($data['days'], $data['interval']);

        $availability->update($data);

        return new AvailabilityResource($availability);
    }

    public function destroy(Availability $availability)
    {
        Gate::authorize('delete', $availability);

        $availability->delete();

        return response()->noContent();
    }
}
