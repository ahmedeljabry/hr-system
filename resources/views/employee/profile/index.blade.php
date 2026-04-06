@extends('layouts.employee')

@section('content')
<div class="mb-8">
    <div class="bg-secondary rounded-3xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center text-4xl text-white shadow-inner">
                {{ substr($employee->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-3xl font-bold">{{ $employee->name }}</h1>
                <p class="text-primary font-medium text-lg">{{ $employee->position ?? __('Employee Profile') }}</p>
            </div>
        </div>
        <div class="absolute top-0 right-0 -mt-16 -mr-16 text-white opacity-10">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Info Section -->
    <div class="md:col-span-1 space-y-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900">{{ __('Job Details') }}</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm text-gray-500 font-medium block mb-1">{{ __('Position') }}</label>
                    <div class="text-gray-900 font-semibold">{{ $employee->position ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500 font-medium block mb-1">{{ __('Hire Date') }}</label>
                    <div class="text-gray-900 font-semibold">{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500 font-medium block mb-1">{{ __('Basic Salary') }}</label>
                    <div class="text-gray-900 font-semibold">{{ $employee->basic_salary ? number_format($employee->basic_salary, 2) : '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900">{{ __('Documents') }}</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- National ID -->
                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex flex-col justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">{{ __('National ID') }}</h4>
                    </div>
                    @if($employee->national_id_image)
                        <a href="{{ route('employee.profile.document', 'national_id') }}" target="_blank" class="block">
                            <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center relative group">
                                <img src="{{ route('employee.profile.document', 'national_id') }}" alt="National ID" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white font-medium bg-black/50 px-4 py-2 rounded-lg">{{ __('View All') }}</span>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="w-full h-32 bg-gray-100/50 border border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm">{{ __('Document not available') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Contract -->
                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex flex-col justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-white rounded-lg shadow-sm text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">{{ __('Contract') }}</h4>
                    </div>
                    @if($employee->contract_image)
                        <a href="{{ route('employee.profile.document', 'contract') }}" target="_blank" class="block">
                            <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center relative group">
                                <img src="{{ route('employee.profile.document', 'contract') }}" alt="Contract" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white font-medium bg-black/50 px-4 py-2 rounded-lg">{{ __('View All') }}</span>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="w-full h-32 bg-gray-100/50 border border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm">{{ __('Document not available') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
