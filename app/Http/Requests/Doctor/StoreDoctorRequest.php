<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'specialty_id' => ['required', 'integer', 'exists:specialties,id'],
            'medical_council_code' => [
                'required',
                'string',
                'max:50',
                'unique:doctors,medical_council_code',
            ],
            'bio' => ['nullable', 'string'],
        ];
    }
}
