<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    public function rules(): array
    {
        $clientId = $this->user()->client?->id;
        $employeeId = $this->route('employee');

        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'national_id_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('employees')->where('client_id', $clientId)->ignore($employeeId),
            ],
            'national_id_image' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:5120'],
            'contract_image' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:5120'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('messages.employee_name')]),
            'national_id_number.unique' => __('messages.national_id_duplicate'),
        ];
    }
}
