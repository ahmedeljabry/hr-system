@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-4xl mx-auto">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.add_discount')" 
            :subtitle="__('messages.discount_desc')"
            :backLink="route('client.payroll.index')"
        />

        @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-100 p-5 rounded-2xl shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-red-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-red-50 border border-red-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <ul class="text-red-800 text-sm font-bold tracking-tight list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
            <form method="POST" action="{{ route('client.deductions.store') }}" class="p-12 space-y-12">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Employee selection -->
                    <div class="space-y-3">
                        <label for="employee_id" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">
                            {{ __('messages.choose_employee') }} <span class="text-primary">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                    </svg>
                                </div>
                            </div>
                            <select name="employee_id" id="employee_id" required
                                class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                                <option value="">{{ __('messages.choose_employee') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }} ({{ number_format($employee->basic_salary, 2) }} {{ __('messages.currency_sar') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Discount Amount -->
                    <div class="space-y-3">
                        <label for="amount" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">
                            {{ __('messages.discount_amount') }} <span class="text-primary">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                <div class="w-8 h-8 rounded-xl bg-rose-50 group-focus-within:bg-rose-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="w-4 h-4 text-rose-400 group-focus-within:text-rose-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.406 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.406-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount') }}" required
                                class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="space-y-3">
                        <label for="deduction_date" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">
                            {{ __('messages.period') }} <span class="text-primary">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 group-focus-within:bg-amber-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="w-4 h-4 text-amber-400 group-focus-within:text-amber-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008z"></path>
                                    </svg>
                                </div>
                            </div>
                            <input type="month" name="deduction_date" id="deduction_date" value="{{ old('deduction_date', date('Y-m')) }}" required
                                class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="space-y-3">
                        <label for="reason" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">
                            {{ __('messages.notes') }}
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 group-focus-within:bg-blue-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="w-4 h-4 text-blue-400 group-focus-within:text-blue-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            <input type="text" name="reason" id="reason" value="{{ old('reason') }}"
                                class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                placeholder="{{ __('messages.add_note_placeholder') }}">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-10 border-t border-gray-50 flex items-center justify-center gap-6">
                    <a href="{{ route('client.payroll.index') }}"
                        class="px-10 py-4 text-gray-500 hover:text-secondary font-black transition-colors">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-16 py-5 bg-rose-500 hover:bg-rose-600 text-white text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(244,63,94,0.3)] hover:shadow-[0_25px_60px_rgba(244,63,94,0.5)] border-b-4 border-rose-700 hover:border-rose-600 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit">
                        <svg class="w-7 h-7 me-4 group-hover/submit:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('messages.add_discount') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
