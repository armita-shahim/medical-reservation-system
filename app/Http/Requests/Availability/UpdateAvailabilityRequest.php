<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hospital_id' => ['sometimes', 'integer', 'exists:hospitals,id'],

            'days' => ['sometimes', 'required', 'array', 'min:1', 'max:7'],
            'days.*' => ['required', 'string', 'distinct', Rule::in(['SA', 'SU', 'MO', 'TU', 'WE', 'FR'])],

            'interval' => ['sometimes', 'integer', 'min:1'],

            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i'],

            'valid_from' => ['sometimes', 'date_format:Y-m-d'],
            'valid_until' => ['sometimes', 'nullable', 'date_format:Y-m-d', 'after_or_equal:valid_from'],

            'slot_duration_minutes' => [
                'sometimes',
                'integer',
                'between:10,120',
                'multiple_of:5',
            ],
        ];
    }
}
