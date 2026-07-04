<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('receptionist');
    }

    public function rules(): array
    {
        $doctorId = $this->route('id');

        return [
            'name'           => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'license_no'     => ['required', 'string', 'max:100', Rule::unique('doctors', 'license_no')->ignore($doctorId)],
        ];
    }
}
