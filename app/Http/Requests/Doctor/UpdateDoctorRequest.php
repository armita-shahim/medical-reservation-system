<?php

namespace App\Http\Requests\Doctor;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isAdmin = $this->user()?->role === UserRole::Admin;

        if ($isAdmin) {
            return [
                'specialty_id' => [
                    'sometimes',
                    'required',
                    'integer',
                    'exists:specialties,id',
                ],

                'medical_council_code' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('doctors', 'medical_council_code')
                        ->ignore($this->route('doctor')),
                ],

                'bio' => ['sometimes', 'nullable', 'string'],
            ];
        } else {
            return [
                'specialty_id' => ['prohibited'],
                'medical_council_code' => ['prohibited'],
                'bio' => ['sometimes', 'nullable', 'string'],
            ];
        }
    }
}
