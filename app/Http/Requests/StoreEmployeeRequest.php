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
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'national_id_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('employees')->where('client_id', $clientId)->ignore($employeeId),
            ],
            'national_id_image' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:10240'],
            'contract_image' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:10240'],
            'cv_file' => ['nullable', 'file', 'mimes:jpeg,png,pdf,doc,docx', 'max:10240'],
            'other_documents' => ['nullable', 'array'],
            'other_documents.*' => ['file', 'mimes:jpeg,png,pdf,doc,docx', 'max:10240'],
            'bank_iban' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'emergency_phone' => ['nullable', 'string', 'max:50'],
            'email' => [
                'required', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($this->getLinkedUserId(), 'id')
            ],
            'password' => [$this->route('employee') ? 'nullable' : 'required', 'string', 'min:8'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'housing_allowance' => ['nullable', 'numeric', 'min:0'],
            'transportation_allowance' => ['nullable', 'numeric', 'min:0'],
            'other_allowances' => ['nullable', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
            'date_of_birth' => ['nullable', 'date'],
        ];
    }

    private function getLinkedUserId(): ?int
    {
        $employeeId = $this->route('employee');
        if (!$employeeId) return null;

        $employee = \App\Models\Employee::find($employeeId);
        return $employee?->user_id;
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => __('validation.required', ['attribute' => __('messages.name_ar')]),
            'name_en.required' => __('validation.required', ['attribute' => __('messages.name_en')]),
            'national_id_number.unique' => __('messages.national_id_duplicate'),
        ];
    }
}
