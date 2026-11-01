<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'hospital_id' => $this->hospital_id,
            'start_at' => $this->start_at->format('Y-m-d H:i:s'),
            'end_at' => $this->end_at->format('Y-m-d H:i:s'),
            'status' => $this->status->value,
            'notes' => $this->notes,
            'cancelled_by_id' => $this->cancelled_by_id,
            'cancelled_at' => $this->cancelled_at?->format('Y-m-d H:i:s'),
            'cancel_reason' => $this->cancel_reason,
            'reminder_sent_at' => $this->reminder_sent_at?->format('Y-m-d H:i:s')
        ];
    }
}
