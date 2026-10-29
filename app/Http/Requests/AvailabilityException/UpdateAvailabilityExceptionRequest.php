<?php

namespace App\Http\Requests\AvailabilityException;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvailabilityExceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_at' => ['sometimes', 'required', 'date_format:Y-m-d\TH:i:sP'],
            'end_at' => [
                'sometimes',
                'required',
                'date_format:Y-m-d\TH:i:sP',
            ],
            'reason' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
