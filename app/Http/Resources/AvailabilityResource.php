<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'hospital_id' => $this->hospital_id,
            'rrule' => $this->rrule,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'valid_from' => $this->valid_from->format('Y-m-d'),
            'valid_until' => $this->valid_until?->format('Y-m-d'),
            'slot_duration_minutes' => $this->slot_duration_minutes,
        ];
    }
}
