@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-7xl mx-auto">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.insurance_policy') . ': ' . $policy->policy_number" 
            :subtitle="__('messages.insurance_policy_desc')"
            :backLink="route('client.medical-insurance.policies.index', ['client_slug' => request('client_slug')])"
        >
            <x-slot name="leading">
                <div class="w-16 h-16 bg-primary/20 rounded-[1.5rem] flex items-center justify-center shrink-0 border border-primary/30 shadow-2xl transition-transform duration-500">
                    <svg class="w-9 h-9 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </x-slot>
            <x-slot name="actions">
                <a href="{{ route('client.medical-insurance.policies.edit', ['client_slug' => request('client_slug'), 'medical_insurance_policy' => $policy->id]) }}" 
                   class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-black rounded-xl transition-all duration-300 backdrop-blur-md">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('messages.edit') }}
                </a>
            </x-slot>
        </x-dashboard-sub-header>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-100 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="bg-green-100 p-2 rounded-xl text-green-600">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-100 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="bg-red-100 p-2 rounded-xl text-red-600">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Info -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Policy Details Card -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <h2 class="text-xl font-black text-secondary mb-6 border-b pb-4">{{ __('messages.view_details') }}</h2>
                    <div class="space-y-6">
                        <div>
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.insurance_company') }}</span>
                            <span class="text-base font-black text-secondary tracking-tight">{{ $policy->insuranceCompany->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.period') }}</span>
                            <span class="text-sm font-bold text-gray-600">{{ $policy->start_date->format('d/m/Y') }} — {{ $policy->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.status') }}</span>
                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $policy->is_expired ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">
                                {{ $policy->is_expired ? __('messages.expired') : __('messages.active') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Excel Import Card -->
                <div class="bg-secondary p-8 rounded-[2.5rem] shadow-sm text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <h2 class="text-xl font-black mb-2">{{ __('messages.upload_policy_data') }}</h2>
                        <p class="text-indigo-200 text-xs font-bold leading-relaxed mb-6">{{ __('messages.import_format_hint') }}</p>
                        
                        <div class="bg-white/10 p-5 rounded-2xl mb-8 border border-white/10">
                            <ul class="text-[10px] font-black uppercase tracking-widest space-y-2 opacity-80">
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                    <span>National ID / رقم الهوية</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                    <span>Insurance Class / الفئة</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                                    <span>Cost / التكلفة</span>
                                </li>
                            </ul>
                        </div>

                        <form action="{{ route('client.medical-insurance.policies.import', ['client_slug' => request('client_slug'), 'policy' => $policy->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="excel_file" class="hidden" id="excel_file" onchange="this.form.submit()">
                            <button type="button" onclick="document.getElementById('excel_file').click()" 
                                    class="w-full py-4 bg-primary hover:bg-[#8affaa] text-secondary font-black rounded-2xl shadow-xl transition-all flex items-center justify-center gap-2 group/btn">
                                <svg class="w-5 h-5 transition-transform group-hover/btn:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span>{{ __('messages.upload_excel') }}</span>
                            </button>
                        </form>
                    </div>
                    <!-- Decorative element -->
                    <div class="absolute -right-8 -bottom-8 bg-white/5 w-32 h-32 rounded-full blur-2xl"></div>
                </div>
            </div>

            <!-- Employee List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 h-full overflow-hidden">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/20 flex items-center justify-between">
                        <h2 class="text-xl font-black text-secondary">{{ __('messages.employees') }}</h2>
                        <span class="px-4 py-2 bg-gray-100 rounded-xl text-xs font-black text-gray-500">{{ $policy->employees->count() }} {{ __('messages.employees_count') }}</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.employee_name') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.insurance_class') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.employee_cost') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($policy->employees as $employee)
                                <tr class="hover:bg-gray-50/50 transition-all">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-secondary">{{ $employee->name }}</span>
                                                <span class="text-[10px] font-bold text-gray-400">{{ $employee->national_id_number }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-black uppercase tracking-widest">
                                            {{ $employee->pivot->insurance_class }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm font-bold text-gray-700">
                                        {{ number_format($employee->pivot->cost, 2) }} {{ __('messages.currency_sar') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-32 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-40 grayscale">
                                            <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            <span class="text-lg font-bold text-gray-500">{{ __('messages.no_records_found') }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
