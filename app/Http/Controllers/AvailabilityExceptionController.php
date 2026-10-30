<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailabilityException\AvailabilityExceptionRequest;
use App\Http\Resources\AvailabilityExceptionResource;
use App\Models\AvailabilityException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;



class AvailabilityExceptionController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;

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

    public function store(AvailabilityExceptionRequest $request)
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

    public function destroy(AvailabilityException $exception)
    {
        Gate::authorize('delete', $exception);

        $exception->delete();

        return response()->noContent();
    }
}
