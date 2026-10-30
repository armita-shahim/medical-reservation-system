<?php

namespace App\Http\Requests\Availability;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hospital_id' => ['required', 'integer', 'exists:hospitals,id'],

            'days' => ['required', 'array', 'min:1', 'max:7'],
            'days.*' => ['required', 'string', 'distinct', Rule::in(['SA', 'SU', 'MO', 'TU', 'WE', 'TH', 'FR'])],
            'interval' => ['sometimes', 'integer', 'min:1'],

            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],

            'valid_from' => ['required', 'date_format:Y-m-d'],
            'valid_until' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:valid_from'],

            'slot_duration_minutes' => ['required', 'integer', 'between:10,120', 'multiple_of:5']
        ];
    }
}
