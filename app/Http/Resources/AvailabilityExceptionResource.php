<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityExceptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'start_at' => $this->start_at->format('Y-m-d H:i:s'),
            'end_at' => $this->end_at->format('Y-m-d H:i:s'),
            'reason' => $this->reason,
        ];
    }
}
