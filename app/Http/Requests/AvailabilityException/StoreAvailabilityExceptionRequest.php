<?php

namespace App\Http\Requests\AvailabilityException;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityExceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_at' => ['required', 'date_format:Y-m-d\TH:i:sP'],
            'end_at' => [
                'required',
                'date_format:Y-m-d\TH:i:sP',
                'after:start_at',
            ],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
