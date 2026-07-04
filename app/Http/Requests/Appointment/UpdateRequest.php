<?php

namespace App\Http\Requests\Appointment;

use App\Enums\AppointmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('doctor');
    }

    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date'],
            'status'       => ['required', Rule::in(AppointmentStatus::values())],
        ];
    }
}
