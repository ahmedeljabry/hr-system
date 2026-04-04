@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('messages.edit_employee') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('messages.back') }} <a href="{{ route('client.employees.index') }}" class="text-blue-600 hover:text-blue-500 underline font-medium">{{ __('messages.employees') }}</a></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form method="POST" action="{{ route('client.employees.update', $employee->id) }}" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.employee_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" required
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 transition-all @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.position') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="position" id="position" value="{{ old('position', $employee->position) }}" required
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 transition-all @error('position') border-red-500 @enderror">
                    @error('position')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- National ID -->
                <div>
                    <label for="national_id_number" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.national_id_number') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="national_id_number" id="national_id_number" value="{{ old('national_id_number', $employee->national_id_number) }}" required
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 transition-all @error('national_id_number') border-red-500 @enderror">
                    @error('national_id_number')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Basic Salary -->
                <div>
                    <label for="basic_salary" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.basic_salary') }} <span class="text-red-500">*</span></label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="basic_salary" id="basic_salary" step="0.01" value="{{ old('basic_salary', $employee->basic_salary) }}" required
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 transition-all @error('basic_salary') border-red-500 @enderror">
                    </div>
                    @error('basic_salary')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hire Date -->
                <div>
                    <label for="hire_date" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.hire_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', $employee->hire_date->format('Y-m-d')) }}" required
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 transition-all @error('hire_date') border-red-500 @enderror">
                    @error('hire_date')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- National ID Image -->
                <div class="md:col-span-1">
                    <label for="national_id_image" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.national_id_image') }}</label>
                    @if($employee->national_id_image)
                        <div class="mb-2 text-xs text-gray-500 truncate italic">
                            {{ __('messages.current_file') }}: {{ basename($employee->national_id_image) }}
                        </div>
                    @endif
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="national_id_image" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>{{ __('messages.change_file') }}</span>
                                    <input id="national_id_image" name="national_id_image" type="file" class="sr-only" accept="image/*,.pdf">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">{{ __('messages.file_size_hint') }}</p>
                        </div>
                    </div>
                    @error('national_id_image')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contract Image -->
                <div class="md:col-span-1">
                    <label for="contract_image" class="block text-sm font-semibold text-gray-700 mb-1 tracking-tight">{{ __('messages.contract_image') }}</label>
                    @if($employee->contract_image)
                        <div class="mb-2 text-xs text-gray-500 truncate italic">
                            {{ __('messages.current_file') }}: {{ basename($employee->contract_image) }}
                        </div>
                    @endif
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="contract_image" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>{{ __('messages.change_file') }}</span>
                                    <input id="contract_image" name="contract_image" type="file" class="sr-only" accept="image/*,.pdf">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">{{ __('messages.file_size_hint') }}</p>
                        </div>
                    </div>
                    @error('contract_image')
                        <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('client.employees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-bold rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    {{ __('messages.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
