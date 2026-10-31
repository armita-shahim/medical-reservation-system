<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hospital\HospitalRequest;
use App\Models\Hospital;
use App\Http\Resources\HospitalResource;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::orderBy('id')
            ->paginate(15);

        return HospitalResource::collection($hospitals);
    }

    public function show(Hospital $hospital)
    {
        return new HospitalResource($hospital);
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
