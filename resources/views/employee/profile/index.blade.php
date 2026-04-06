@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <!-- Standard Header -->
    <x-dashboard-sub-header 
        :title="$employee->name" 
        :subtitle="$employee->position ?? __('Employee Profile')"
    >
        <x-slot name="leading">
            <div class="w-16 h-16 bg-white/20 rounded-[1.5rem] flex items-center justify-center text-3xl text-white shadow-xl group-hover:scale-110 transition-transform duration-500 overflow-hidden">
                {{ mb_substr($employee->name, 0, 1) }}
            </div>
        </x-slot>
    </x-dashboard-sub-header>


<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Info Section -->
    <div class="md:col-span-1 space-y-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900">{{ __('messages.job_details') }}</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm text-gray-500 font-medium block mb-1">{{ __('messages.position') }}</label>
                    <div class="text-gray-900 font-semibold">{{ $employee->position ?? __('messages.not_applicable') }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500 font-medium block mb-1">{{ __('messages.hire_date') }}</label>
                    <div class="text-gray-900 font-semibold">{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : __('messages.not_applicable') }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500 font-medium block mb-1">{{ __('messages.basic_salary') }}</label>
                    <div class="text-gray-900 font-semibold">{{ $employee->basic_salary ? number_format($employee->basic_salary, 2) : __('messages.not_applicable') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-900">{{ __('messages.documents') }}</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- National ID -->
                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex flex-col justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-white rounded-lg shadow-sm text-indigo-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">{{ __('messages.national_id_image') }}</h4>
                    </div>
                    @if($employee->national_id_image)
                        <a href="{{ route('employee.profile.document', 'national_id') }}" target="_blank" class="block">
                            <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center relative group">
                                <img src="{{ route('employee.profile.document', 'national_id') }}" alt="National ID" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white font-medium bg-black/50 px-4 py-2 rounded-lg">{{ __('messages.view_all') }}</span>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="w-full h-32 bg-gray-100/50 border border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm">{{ __('messages.document_not_available') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Contract -->
                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50 flex flex-col justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-white rounded-lg shadow-sm text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">{{ __('messages.contract') }}</h4>
                    </div>
                    @if($employee->contract_image)
                        <a href="{{ route('employee.profile.document', 'contract') }}" target="_blank" class="block">
                            <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center relative group">
                                <img src="{{ route('employee.profile.document', 'contract') }}" alt="Contract" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white font-medium bg-black/50 px-4 py-2 rounded-lg">{{ __('messages.view_all') }}</span>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="w-full h-32 bg-gray-100/50 border border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm">{{ __('messages.document_not_available') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
