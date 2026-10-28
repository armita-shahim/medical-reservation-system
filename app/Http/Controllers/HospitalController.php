<?php

namespace App\Http\Controllers;

use App\Http\Requests\HospitalRequest;
use App\Models\Hospital;
use App\Http\Resources\HospitalResource;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::where('is_active', true)
            ->orderBy('id')
            ->paginate(15);

        return HospitalResource::collection($hospitals);
    }

    public function store(HospitalRequest $request)
    {
        $hospital = Hospital::create($request->validated());

        return (new HospitalResource($hospital))
            ->response()
            ->setStatusCode(201);
    }

    public function update(HospitalRequest $request, Hospital $hospital)
    {
        $hospital->update($request->validated());

        return new HospitalResource($hospital);
    }
}
