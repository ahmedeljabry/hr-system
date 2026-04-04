@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('messages.employee_details') }}</h1>
            <p class="mt-2 text-sm text-gray-500">{{ __('messages.back') }} <a href="{{ route('client.employees.index') }}" class="text-blue-600 hover:text-blue-500 underline font-medium">{{ __('messages.employees') }}</a></p>
        </div>
        <div class="flex items-center gap-3">
            @if(is_null($employee->user_id))
                <a href="{{ route('client.employees.create-account', $employee) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-all">
                    {{ __('messages.create_account') }}
                </a>
            @endif
            <a href="{{ route('client.salary-components.index', $employee) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all">
                {{ __('messages.salary_components') }}
            </a>
            <a href="{{ route('client.employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                {{ __('messages.edit') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="md:col-span-2 space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('messages.employee_details') }}</h2>
                </div>
                <div class="p-8">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-10">
                        <div>
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.employee_name') }}</dt>
                            <dd class="mt-2 text-lg font-semibold text-gray-900">{{ $employee->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.position') }}</dt>
                            <dd class="mt-2 text-lg font-semibold text-gray-900">{{ $employee->position }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.national_id_number') }}</dt>
                            <dd class="mt-2 text-lg font-semibold text-gray-900 font-mono">{{ $employee->national_id_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.hire_date') }}</dt>
                            <dd class="mt-2 text-lg font-semibold text-gray-900">{{ $employee->hire_date->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.basic_salary') }}</dt>
                            <dd class="mt-2 text-2xl font-black text-blue-600">{{ number_format($employee->basic_salary, 2) }} <span class="text-sm font-normal text-gray-400">SAR</span></dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar / Files -->
        <div class="space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-tight">{{ __('messages.documents') }}</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- National ID File -->
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">{{ __('messages.national_id_image') }}</p>
                        @if($employee->national_id_image)
                            <a href="{{ route('client.files.employee', [$employee->id, 'national_id']) }}" class="group flex items-center p-3 border border-gray-100 rounded-lg hover:border-blue-200 hover:bg-blue-50 transition-all">
                                <div class="p-2 bg-blue-100 text-blue-600 rounded mr-3">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-semibold text-gray-700 truncate group-hover:text-blue-600">{{ basename($employee->national_id_image) }}</p>
                                    <p class="text-xs text-gray-400 uppercase">{{ __('messages.view') }}</p>
                                </div>
                            </a>
                        @else
                            <div class="flex items-center p-3 border border-gray-50 rounded-lg bg-gray-25 italic">
                                <span class="text-xs text-gray-400">{{ __('messages.no_file') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Contract File -->
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">{{ __('messages.contract_image') }}</p>
                        @if($employee->contract_image)
                            <a href="{{ route('client.files.employee', [$employee->id, 'contract']) }}" class="group flex items-center p-3 border border-gray-100 rounded-lg hover:border-blue-200 hover:bg-blue-50 transition-all">
                                <div class="p-2 bg-green-100 text-green-600 rounded mr-3">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-semibold text-gray-700 truncate group-hover:text-green-600">{{ basename($employee->contract_image) }}</p>
                                    <p class="text-xs text-gray-400 uppercase">{{ __('messages.view') }}</p>
                                </div>
                            </a>
                        @else
                            <div class="flex items-center p-3 border border-gray-50 rounded-lg bg-gray-25 italic">
                                <span class="text-xs text-gray-400">{{ __('messages.no_file') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
