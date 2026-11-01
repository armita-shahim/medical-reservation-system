<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Http\Resources\AvailabilityResource;
use App\Http\Requests\Availability\AvailabilityRequest;

class AvailabilityController extends Controller
{
    public function index()
    {
        $doctor = request()->user()->doctor;

        $availabilities = $doctor->availabilities()
            ->orderBy('id')
            ->paginate(15);

        return AvailabilityResource::collection($availabilities);
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
        $data = $request->validated();
        if ($request->exists('valid_until')) {
            $data['valid_until'] = $request->input('valid_until');
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
        $availability->delete();

        return response()->noContent();
    }
}
