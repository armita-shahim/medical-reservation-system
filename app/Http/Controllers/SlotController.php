<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlotIndexRequest;
use App\Models\Doctor;
use App\Services\SlotService;

class SlotController extends Controller
{
    public function index(SlotIndexRequest $request, Doctor $doctor, SlotService $slotService)
    {
        $validated = $request->validated();

        if (isset($validated['hospital_id'])) {
            $hospitalId = (int) $validated['hospital_id'];
        } else {
            $hospitalId = null;
        }

        $slots = $slotService->generate(
            $doctor,
            $validated['from'],
            $hospitalId
        );

        return response()->json([
            'doctor_id' => $doctor->id,
            'slots' => $slots,
        ]);
    }
}
