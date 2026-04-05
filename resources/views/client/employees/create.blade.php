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
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                            </svg>
                                        </div>
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
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 group-focus-within:bg-blue-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-blue-400 group-focus-within:text-blue-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                                            </svg>
                                        </div>
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
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-amber-50 group-focus-within:bg-amber-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-amber-400 group-focus-within:text-amber-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="password" name="password" id="password" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-14' : 'pl-14 pr-14' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="••••••••">
                                    <button type="button" onclick="togglePassword('password', this)"
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-3' : 'right-3' }} flex items-center transition-all duration-300 group/eye">
                                        <div class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer">
                                            <svg class="w-4 h-4 text-gray-400 eye-open" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <svg class="w-4 h-4 text-gray-400 eye-closed hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="space-y-3">
                                <label for="phone"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.phone_field') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-violet-50 group-focus-within:bg-violet-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-violet-400 group-focus-within:text-violet-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"></path>
                                            </svg>
                                        </div>
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
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-red-50 group-focus-within:bg-red-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-red-400 group-focus-within:text-red-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0l6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 014.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 00-.38 1.21 12.035 12.035 0 007.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 011.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 01-2.25 2.25h-2.25z"></path>
                                            </svg>
                                        </div>
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
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-teal-50 group-focus-within:bg-teal-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-teal-400 group-focus-within:text-teal-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a48.667 48.667 0 00-1.37 8.558M12 10.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM12 10.5c0 .834-.09 1.65-.259 2.434m-4.982 5.822a48.547 48.547 0 00-4.709.386"></path>
                                            </svg>
                                        </div>
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
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-50 group-focus-within:bg-emerald-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-emerald-400 group-focus-within:text-emerald-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"></path>
                                            </svg>
                                        </div>
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
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-50 group-focus-within:bg-indigo-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-indigo-400 group-focus-within:text-indigo-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="text" name="position" id="position" value="{{ old('position') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.position_placeholder') }}">
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div class="space-y-3">
                                <label for="date_of_birth"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.date_of_birth') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-orange-50 group-focus-within:bg-orange-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-orange-400 group-focus-within:text-orange-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 21v-4.5h1.5V21M3.375 7.5a1.125 1.125 0 00-1.125 1.125v10.5c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125V8.625A1.125 1.125 0 0020.625 7.5H3.375zM12 15a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zM4.5 7.5v-3a2.25 2.25 0 012.25-2.25h10.5A2.25 2.25 0 0119.5 4.5v3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
                                </div>
                            </div>

                            <!-- Hire Date -->
                            <div class="space-y-3">
                                <label for="hire_date"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.hire_date') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-rose-50 group-focus-within:bg-rose-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-rose-400 group-focus-within:text-rose-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path>
                                            </svg>
                                        </div>
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

@push('scripts')
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
@endpush