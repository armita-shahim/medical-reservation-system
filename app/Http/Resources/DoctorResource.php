<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenLoaded('user', function () {
                return $this->user->name;
            }),
            'specialty' => $this->whenLoaded('specialty', function () {
                return $this->specialty->name;
            }),
            'medical_council_code' => $this->medical_council_code,
            'bio' => $this->bio
        ];
    }
}
