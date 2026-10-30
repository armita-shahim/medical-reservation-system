<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'integer', 'exists:doctors,id'],
            'hospital_id' => ['required', 'integer', 'exists:hospitals,id'],
            'start_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
