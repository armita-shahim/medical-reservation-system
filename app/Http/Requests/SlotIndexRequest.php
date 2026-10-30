<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlotIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'hospital_id' => [
                'nullable',
                'integer',
                'exists:hospitals,id',
            ],
        ];
    }
}
