@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center gap-6">
                    <x-avatar :name="app()->getLocale() == 'ar' ? $employee->name_ar : ($employee->name_en ?? $employee->name_ar)" size="2xl" class="shadow-2xl border-4 border-white/10" />
                    <div>
                        <h1 class="text-4xl font-extrabold mb-1 tracking-tight text-primary">{{ app()->getLocale() == 'ar' ? $employee->name_ar : ($employee->name_en ?? $employee->name_ar) }}</h1>
                        <p class="text-gray-300 text-lg flex items-center gap-2 opacity-90">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-xs font-black uppercase tracking-widest bg-primary/10 text-primary border border-primary/20">
                                {{ $employee->position }}
                            </span>
                            <span class="text-white/40">•</span>
                            <span>{{ __('messages.hire_date') }}: {{ $employee->hire_date->format('d/m/Y') }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-4">
                    @if(is_null($employee->user_id))
                        <a href="{{ route('client.employees.create-account', $employee) }}" 
                           class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-sm font-black rounded-2xl transition-all duration-300 shadow-lg">
                            {{ __('messages.create_account') }}
                        </a>
                    @endif
                    <a href="{{ route('client.employees.edit', $employee) }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold rounded-2xl transition-all duration-300 backdrop-blur-md">
                        <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        {{ __('messages.edit') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Details -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Financial Summary Card -->
                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
                    <div class="p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-1c.51-.418.815-1.002.815-1.599 0-1.105-1.343-2-3-2s-3 .895-3 2 1.343 2 3 2m.599 1.599c-.51.418-.815 1.002-.815 1.599 0 1.105 1.343 2 3 2s3-.895 3-2-1.343-2-3-2m-3.599 1.599c.51.418.815 1.002.815 1.599"></path></svg>
                            </div>
                            <h2 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.total_salary') }}</h2>
                            <div class="ms-auto">
                                <span class="text-4xl font-black text-primary">{{ number_format($employee->total_salary, 2) }}</span>
                                <span class="text-sm font-bold text-gray-400 ms-1 uppercase tracking-widest">SAR</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.basic_salary') }}</span>
                                <span class="text-lg font-black text-secondary">{{ number_format($employee->basic_salary, 2) }}</span>
                            </div>
                            <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.housing_allowance') }}</span>
                                <span class="text-lg font-black text-secondary">{{ number_format($employee->housing_allowance, 2) }}</span>
                            </div>
                            <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.transportation_allowance') }}</span>
                                <span class="text-lg font-black text-secondary">{{ number_format($employee->transportation_allowance, 2) }}</span>
                            </div>
                            <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.other_allowances') }}</span>
                                <span class="text-lg font-black text-secondary">{{ number_format($employee->other_allowances, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal & Contact Information -->
                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
                    <div class="p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-secondary/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h2 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.personal_information') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-8">
                            <div class="space-y-1">
                                <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.national_id_number') }}</dt>
                                <dd class="text-lg font-black text-secondary font-mono">{{ $employee->national_id_number }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.date_of_birth') }}</dt>
                                <dd class="text-lg font-black text-secondary">{{ $employee->date_of_birth ? $employee->date_of_birth->format('d/m/Y') : '—' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.bank_iban') }}</dt>
                                <dd class="text-lg font-black text-secondary font-mono">{{ $employee->bank_iban ?: '—' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.phone') }}</dt>
                                <dd class="text-lg font-black text-secondary">{{ $employee->phone ?: '—' }}</dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.emergency_phone') }}</dt>
                                <dd class="text-lg font-black text-secondary">{{ $employee->emergency_phone ?: '—' }}</dd>
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.email') }}</dt>
                                <dd class="text-lg font-black text-secondary">{{ $employee->email ?: ($employee->user?->email ?: '—') }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Documents -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden sticky top-8">
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h2 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.documents') }}</h2>
                        </div>

                        <div class="space-y-4">
                            <!-- ID Copy -->
                            @include('client.employees._doc-action', [
                                'label' => __('messages.national_id_image'),
                                'file' => $employee->national_id_image,
                                'type' => 'national_id'
                            ])

                            <!-- CV -->
                            @include('client.employees._doc-action', [
                                'label' => __('messages.cv_file'),
                                'file' => $employee->cv_file,
                                'type' => 'cv'
                            ])

                            <!-- Contract -->
                            @include('client.employees._doc-action', [
                                'label' => __('messages.contract_image'),
                                'file' => $employee->contract_image,
                                'type' => 'contract'
                            ])

                            <!-- Other Documents -->
                            @if(!empty($employee->other_documents))
                                <div class="pt-4 border-t border-gray-50">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 italic">{{ __('messages.other_documents') }}</p>
                                    <div class="space-y-3">
                                        @foreach($employee->other_documents as $index => $path)
                                            <a href="{{ route('client.files.employee', [$employee->id, 'other', 'index' => $index]) }}" 
                                               class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 hover:bg-primary/10 border border-transparent transition-all group/doc">
                                                <div class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center group-hover/doc:text-primary transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <span class="text-xs font-bold text-secondary truncate">{{ basename($path) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
