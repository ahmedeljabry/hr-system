@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Premium Hero Section -->
            <div
                class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">
                            {{ __('messages.add_employee') }}</h1>
                        <p class="text-gray-300 text-lg opacity-90">{{ __('messages.back') }} <a
                                href="{{ route('client.employees.index') }}"
                                class="text-primary hover:underline font-bold">{{ __('messages.employees') }}</a></p>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('client.employees.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold rounded-2xl transition-all duration-300 backdrop-blur-md group/back">
                            <svg class="w-5 h-5 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="bg-red-50 border border-red-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
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
            <div
                class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
                <form method="POST" action="{{ route('client.employees.store') }}" enctype="multipart/form-data"
                    class="p-12 space-y-16">
                    @csrf

                    <!-- Section: Personal & Account Information -->
                    <div class="space-y-10">
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-secondary tracking-tight">
                                {{ __('messages.personal_account_information') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Employee Name -->
                            <div class="md:col-span-2 space-y-3">
                                <label for="name"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.employee_name') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.name_placeholder') }}">
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="space-y-3">
                                <label for="email"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.email') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="employee@example.com">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="space-y-3">
                                <label for="password"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.password') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="password" name="password" id="password" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-3">
                                <label for="phone"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.phone_field') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="+966 50 000 0000">
                                </div>
                            </div>

                            <!-- Emergency Phone -->
                            <div class="space-y-3">
                                <label for="emergency_phone"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.emergency_phone') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="emergency_phone" id="emergency_phone"
                                        value="{{ old('emergency_phone') }}"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="+966 50 000 0000">
                                </div>
                            </div>

                            <!-- National ID / Residency Number -->
                            <div class="space-y-3">
                                <label for="national_id_number"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.national_id_number') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-4m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="national_id_number" id="national_id_number"
                                        value="{{ old('national_id_number') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.id_placeholder') }}">
                                </div>
                            </div>

                            <!-- Bank IBAN -->
                            <div class="space-y-3">
                                <label for="bank_iban"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.bank_iban') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="bank_iban" id="bank_iban" value="{{ old('bank_iban') }}"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="SA00 0000 0000 0000 0000 0000">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Financial & Employment -->
                    <div class="space-y-10">
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-1c.51-.418.815-1.002.815-1.599 0-1.105-1.343-2-3-2s-3 .895-3 2 1.343 2 3 2m.599 1.599c-.51.418-.815 1.002-.815 1.599 0 1.105 1.343 2 3 2s3-.895 3-2-1.343-2-3-2m-3.599 1.599c.51.418.815 1.002.815 1.599">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-secondary tracking-tight">
                                {{ __('messages.employment_details') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Position -->
                            <div class="space-y-3">
                                <label for="position"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.position') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="text" name="position" id="position" value="{{ old('position') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.position_placeholder') }}">
                                </div>
                            </div>

                            <!-- Hire Date -->
                            <div class="space-y-3">
                                <label for="hire_date"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.hire_date') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pe-6' : 'left-0 ps-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}"
                                        required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
                                </div>
                            </div>

                            <!-- Financial Breakdown Grid -->
                            <div
                                class="md:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-6 p-8 bg-gray-50/50 rounded-[2rem] border border-gray-100">
                                <!-- Basic Salary -->
                                <div class="space-y-3">
                                    <label for="basic_salary"
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.basic_salary') }}
                                        <span class="text-primary">*</span></label>
                                    <div class="relative group">
                                        <input type="number" name="basic_salary" id="basic_salary" step="0.01"
                                            value="{{ old('basic_salary') }}" required
                                            class="block w-full bg-white border-2 border-transparent focus:border-primary rounded-xl py-3 px-4 text-secondary font-black transition-all duration-300 outline-none shadow-sm"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                <!-- Housing -->
                                <div class="space-y-3">
                                    <label for="housing_allowance"
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.housing_allowance') }}</label>
                                    <div class="relative group">
                                        <input type="number" name="housing_allowance" id="housing_allowance" step="0.01"
                                            value="{{ old('housing_allowance') }}"
                                            class="block w-full bg-white border-2 border-transparent focus:border-primary rounded-xl py-3 px-4 text-secondary font-black transition-all duration-300 outline-none shadow-sm"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                <!-- Transportation -->
                                <div class="space-y-3">
                                    <label for="transportation_allowance"
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.transportation_allowance') }}</label>
                                    <div class="relative group">
                                        <input type="number" name="transportation_allowance" id="transportation_allowance"
                                            step="0.01" value="{{ old('transportation_allowance') }}"
                                            class="block w-full bg-white border-2 border-transparent focus:border-primary rounded-xl py-3 px-4 text-secondary font-black transition-all duration-300 outline-none shadow-sm"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                <!-- Others -->
                                <div class="space-y-3">
                                    <label for="other_allowances"
                                        class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.other_allowances') }}</label>
                                    <div class="relative group">
                                        <input type="number" name="other_allowances" id="other_allowances" step="0.01"
                                            value="{{ old('other_allowances') }}"
                                            class="block w-full bg-white border-2 border-transparent focus:border-primary rounded-xl py-3 px-4 text-secondary font-black transition-all duration-300 outline-none shadow-sm"
                                            placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Document Management -->
                    <div class="space-y-10">
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-secondary tracking-tight">
                                {{ __('messages.required_documents') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- ID / IQAMA Copy -->
                            <div class="space-y-3">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.national_id_image') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="national_id_image"
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        accept="image/*,.pdf">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('messages.upload_file') ?? 'Upload File' }}</span>
                                    </div>
                                </label>
                            </div>

                            <!-- CV / Resume -->
                            <div class="space-y-3">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.cv_file') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="cv_file"
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        accept=".pdf,.doc,.docx">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('messages.upload_file') ?? 'Upload File' }}</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Contract -->
                            <div class="space-y-3">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.contract_image') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="contract_image"
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        accept="image/*,.pdf">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 20a10.003 10.003 0 006.255-2.164l.054.09A9.991 9.991 0 0112 22c-5.523 0-10-4.477-10-10 0-2.39 1.833-4.35 4.14-4.632M12 11c0-5.523 4.477-10 10-10 2.39 0 4.35 1.833 4.632 4.14M12 11c0 5.523-4.477 10-10 10-2.39 0-4.35-1.833-4.632-4.14M12 11c0-5.523 4.477-10 10-10">
                                            </path>
                                        </svg>
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('messages.upload_file') ?? 'Upload File' }}</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Other Documents -->
                            <div class="space-y-3">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.other_documents_label') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="other_documents[]" multiple
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">{{ __('messages.select_multiple_files') }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-10 border-t border-gray-50 flex items-center justify-center gap-6">
                        <a href="{{ route('client.employees.index') }}"
                            class="px-10 py-4 text-gray-500 hover:text-secondary font-black transition-colors">
                            {{ __('messages.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-20 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit">
                            <svg class="w-7 h-7 me-4 group-hover/submit:scale-110 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            {{ __('messages.add_employee') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection