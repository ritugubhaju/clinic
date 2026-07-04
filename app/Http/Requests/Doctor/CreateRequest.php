<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('receptionist');
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:255'],
            'license_no'     => ['required', 'string', 'max:100', 'unique:doctors,license_no'],
        ];
    }
}
