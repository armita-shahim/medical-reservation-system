<?php

namespace App\Http\Controllers;

use App\Http\Requests\Specialty\SpecialtyRequest;
use App\Http\Resources\SpecialtyResource;
use App\Models\Specialty;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::orderBy('id')->get();
        return SpecialtyResource::collection($specialties);
    }

    public function show(Specialty $specialty)
    {
        return new SpecialtyResource($specialty);
    }

    public function store(SpecialtyRequest $request)
    {
        $specialty = Specialty::create($request->validated());

        return (new SpecialtyResource($specialty))
            ->response()
            ->setStatusCode(201);
    }

    public function update(SpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->validated());

        return new SpecialtyResource($specialty);
    }
}
