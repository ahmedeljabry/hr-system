@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-full mx-auto">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.add_employee')" 
            :subtitle="__('messages.employees')"
            :backLink="route('client.employees.index')"
        />


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
                            <!-- Arabic Name -->
                            <div class="space-y-3">
                                <label for="name_ar"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.name_ar') }}
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
                                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.name_ar_placeholder') }}">
                                </div>
                            </div>

                            <!-- English Name -->
                            <div class="space-y-3">
                                <label for="name_en"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.name_en') }}
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
                                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.name_en_placeholder') }}">
                                </div>
                            </div>

                            <!-- Gender & Nationality -->
                        @php
                            $nationalitiesList = ['Saudi', 'Egyptian', 'Yemeni', 'Jordanian', 'Syrian', 'Sudanese', 'Palestinian', 'Lebanese', 'Moroccan', 'Tunisian', 'Algerian', 'Indian', 'Pakistani', 'Bangladeshi', 'Filipino', 'Afghan', 'Indonesian', 'Nepalese', 'Sri Lankan', 'Ethiopian'];
                            $currentNationality = old('nationality', 'Saudi');
                            $isOther = !in_array($currentNationality, $nationalitiesList);
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:col-span-2" 
                            x-data="{ 
                                nationality: '{{ $isOther ? 'Other' : $currentNationality }}',
                                otherNationality: '{{ $isOther ? $currentNationality : '' }}'
                            }">
                                <!-- Gender -->
                                <div class="space-y-3">
                                    <label for="gender"
                                        class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.gender') }}
                                        <span class="text-primary">*</span></label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                            <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                                <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <select name="gender" id="gender" required
                                            class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>{{ __('messages.select_leave_type_placeholder') }}</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                                        </select>
                                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nationality -->
                                <div class="space-y-3">
                                    <label for="nationality"
                                        class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.nationality') }}
                                        <span class="text-primary">*</span></label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                            <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                                <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <select id="nationality_select" required x-model="nationality"
                                            class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                                            <option value="Saudi">{{ __('messages.saudi') }}</option>
                                            <option value="Egyptian">{{ __('messages.egyptian') }}</option>
                                            <option value="Yemeni">{{ __('messages.yemeni') }}</option>
                                            <option value="Jordanian">{{ __('messages.jordanian') }}</option>
                                            <option value="Syrian">{{ __('messages.syrian') }}</option>
                                            <option value="Sudanese">{{ __('messages.sudanese') }}</option>
                                            <option value="Palestinian">{{ __('messages.palestinian') }}</option>
                                            <option value="Lebanese">{{ __('messages.lebanese') }}</option>
                                            <option value="Moroccan">{{ __('messages.moroccan') }}</option>
                                            <option value="Tunisian">{{ __('messages.tunisian') }}</option>
                                            <option value="Algerian">{{ __('messages.algerian') }}</option>
                                            <option value="Indian">{{ __('messages.indian') }}</option>
                                            <option value="Pakistani">{{ __('messages.pakistani') }}</option>
                                            <option value="Bangladeshi">{{ __('messages.bangladeshi') }}</option>
                                            <option value="Filipino">{{ __('messages.filipino') }}</option>
                                            <option value="Afghan">{{ __('messages.afghan') }}</option>
                                            <option value="Indonesian">{{ __('messages.indonesian') }}</option>
                                            <option value="Nepalese">{{ __('messages.nepalese') }}</option>
                                            <option value="Sri Lankan">{{ __('messages.sri_lankan') }}</option>
                                            <option value="Ethiopian">{{ __('messages.ethiopian') }}</option>
                                            <option value="Other">{{ __('messages.other') }}</option>
                                        </select>
                                        <input type="hidden" name="nationality" :value="nationality === 'Other' ? otherNationality : nationality">
                                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Other Nationality Input -->
                                <div x-show="nationality === 'Other'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">
                                    <label for="other_nationality" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.other_nationality') }} <span class="text-primary">*</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                            <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                                <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <input type="text" x-model="otherNationality" id="other_nationality" class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none" placeholder="{{ __('messages.other_nationality') }}" :required="nationality === 'Other'">
                                    </div>
                                </div>

                                <!-- Saudi Specific Field -->
                                <div x-show="nationality === 'Saudi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="md:col-span-2 space-y-3">
                                    <label for="national_id_number"
                                        class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.national_id_number') }}
                                        <span class="text-primary">*</span></label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                            <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                                <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a48.667 48.667 0 00-1.37 8.558M12 10.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM12 10.5c0 .834-.09 1.65-.259 2.434m-4.982 5.822a48.547 48.547 0 00-4.709.386"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <input type="text" name="national_id_number" id="national_id_number"
                                            value="{{ old('national_id_number') }}"
                                            class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                            placeholder="{{ __('messages.id_placeholder') }}"
                                            :required="nationality === 'Saudi'">
                                    </div>
                                </div>

                                <!-- Residency Details (Conditional for Non-Saudis) -->
                                <div x-show="nationality !== 'Saudi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 p-8 bg-amber-50/30 rounded-[2rem] border border-amber-100/50 mt-4">
                                    <!-- Residency Number -->
                                    <div class="space-y-3">
                                        <label for="residency_number" class="block text-[10px] font-black text-amber-600 uppercase tracking-[0.2em]">{{ __('messages.residency_number') }} <span class="text-primary">*</span></label>
                                        <input type="text" name="residency_number" id="residency_number" value="{{ old('residency_number') }}"
                                            class="block w-full bg-white border-2 border-transparent focus:border-amber-400 rounded-xl py-3 px-4 text-secondary font-black transition-all outline-none"
                                            :required="nationality !== 'Saudi'">
                                    </div>
                                    <!-- Residency Start -->
                                    <div class="space-y-3">
                                        <label for="residency_start_date" class="block text-[10px] font-black text-amber-600 uppercase tracking-[0.2em]">{{ __('messages.residency_start_date') }}</label>
                                        <input type="date" name="residency_start_date" id="residency_start_date" value="{{ old('residency_start_date') }}"
                                            class="block w-full bg-white border-2 border-transparent focus:border-amber-400 rounded-xl py-3 px-4 text-secondary font-black transition-all outline-none">
                                    </div>
                                    <!-- Residency End -->
                                    <div class="space-y-3">
                                        <label for="residency_end_date" class="block text-[10px] font-black text-amber-600 uppercase tracking-[0.2em]">{{ __('messages.residency_end_date') }}</label>
                                        <input type="date" name="residency_end_date" id="residency_end_date" value="{{ old('residency_end_date') }}"
                                            class="block w-full bg-white border-2 border-transparent focus:border-amber-400 rounded-xl py-3 px-4 text-secondary font-black transition-all outline-none">
                                    </div>
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
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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


                            <!-- Bank IBAN -->
                            <div class="space-y-3">
                                <label for="bank_iban"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.bank_iban') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="text" name="position" id="position" value="{{ old('position') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.position_placeholder') }}">
                                </div>
                            </div>

                            <!-- Official Job Title -->
                            <div class="space-y-3">
                                <label for="official_job_title"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.official_job_title') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .407.11.785.3 1.115l-5.826 5.826a2.99 2.99 0 00-1.115-.3c-.231 0-.454.035-.664.1L3.836 11.35c.21-.065.433-.1.664-.1.407 0 .785.11 1.115.3l5.826-5.826a2.99 2.99 0 00-.3-1.115c0-.231.035-.454.1-.664L11.35 3.836z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5l3 3 6-6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="text" name="official_job_title" id="official_job_title" value="{{ old('official_job_title') }}" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="{{ __('messages.official_job_title_placeholder') }}">
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

                            <!-- Annual Leave Days -->
                            <div class="space-y-3">
                                <label for="annual_leave_days"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.annual_leave_days') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="number" name="annual_leave_days" id="annual_leave_days" value="{{ old('annual_leave_days', 21) }}" required min="0"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                        placeholder="21">
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

                    <!-- Section: Working Hours & Type -->
                    <div class="space-y-10">
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-secondary tracking-tight">
                                {{ __('messages.working_hours') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                            <!-- Work Type -->
                            <div class="space-y-3">
                                <label for="work_type"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.work_type') }}
                                    <span class="text-primary">*</span></label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <select name="work_type" id="work_type" required
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                                        <option value="full-time" {{ old('work_type') == 'full-time' ? 'selected' : '' }}>{{ __('messages.full_time') }}</option>
                                        <option value="part-time" {{ old('work_type') == 'part-time' ? 'selected' : '' }}>{{ __('messages.part_time') }}</option>
                                        <option value="remote" {{ old('work_type') == 'remote' ? 'selected' : '' }}>{{ __('messages.remote') }}</option>
                                        <option value="temporary" {{ old('work_type') == 'temporary' ? 'selected' : '' }}>{{ __('messages.temporary') }}</option>
                                        <option value="casual" {{ old('work_type') == 'casual' ? 'selected' : '' }}>{{ __('messages.casual') }}</option>
                                        <option value="seasonal" {{ old('work_type') == 'seasonal' ? 'selected' : '' }}>{{ __('messages.seasonal') }}</option>
                                    </select>
                                    <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Shift Start -->
                            <div class="space-y-3">
                                <label for="shift_start_time"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.shift_start_time') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="time" name="shift_start_time" id="shift_start_time" value="{{ old('shift_start_time', '08:00') }}"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
                                </div>
                            </div>

                            <!-- Shift End -->
                            <div class="space-y-3">
                                <label for="shift_end_time"
                                    class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.shift_end_time') }}</label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                        <div class="w-8 h-8 rounded-xl bg-rose-50 group-focus-within:bg-rose-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                            <svg class="w-4 h-4 text-rose-400 group-focus-within:text-rose-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <input type="time" name="shift_end_time" id="shift_end_time" value="{{ old('shift_end_time', '17:00') }}"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
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
                            <div class="space-y-3" x-data="{ fileName: '' }">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.national_id_image') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="national_id_image"
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        accept="image/*,.pdf"
                                        @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center overflow-hidden px-4">
                                        <svg x-show="!fileName" class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <svg x-show="fileName" class="w-6 h-6 text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span x-text="fileName ? fileName : '{{ __('messages.upload_file') ?? 'Upload File' }}'"
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center truncate w-full"></span>
                                    </div>
                                </label>
                            </div>

                            <!-- CV / Resume -->
                            <div class="space-y-3" x-data="{ fileName: '' }">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.cv_file') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="cv_file"
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        accept=".pdf,.doc,.docx"
                                        @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center overflow-hidden px-4">
                                        <svg x-show="!fileName" class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <svg x-show="fileName" class="w-6 h-6 text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span x-text="fileName ? fileName : '{{ __('messages.upload_file') ?? 'Upload File' }}'"
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center truncate w-full"></span>
                                    </div>
                                </label>
                            </div>

                            <!-- Contract -->
                            <div class="space-y-3" x-data="{ fileName: '' }">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.contract_image') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="contract_image"
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        accept="image/*,.pdf"
                                        @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center overflow-hidden px-4">
                                        <svg x-show="!fileName" class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 20a10.003 10.003 0 006.255-2.164l.054.09A9.991 9.991 0 0112 22c-5.523 0-10-4.477-10-10 0-2.39 1.833-4.35 4.14-4.632M12 11c0-5.523 4.477-10 10-10 2.39 0 4.35 1.833 4.632 4.14M12 11c0 5.523-4.477 10-10 10-2.39 0-4.35-1.833-4.632-4.14M12 11c0-5.523 4.477-10 10-10">
                                            </path>
                                        </svg>
                                        <svg x-show="fileName" class="w-6 h-6 text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span x-text="fileName ? fileName : '{{ __('messages.upload_file') ?? 'Upload File' }}'"
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center truncate w-full"></span>
                                    </div>
                                </label>
                            </div>

                            <!-- Other Documents -->
                            <div class="space-y-3" x-data="{ fileCount: 0 }">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.other_documents_label') }}</label>
                                <label class="relative group block h-32 cursor-pointer">
                                    <input type="file" name="other_documents[]" multiple
                                        class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer"
                                        @change="fileCount = $event.target.files.length">
                                    <div
                                        class="absolute inset-0 border-2 border-dashed border-gray-100 bg-gray-50 rounded-2xl group-hover:border-primary group-hover:bg-primary/5 transition-all duration-300 flex flex-col items-center justify-center overflow-hidden px-4">
                                        <svg x-show="fileCount === 0" class="w-6 h-6 text-gray-300 group-hover:text-primary mb-1 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <svg x-show="fileCount > 0" class="w-6 h-6 text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span x-text="fileCount > 0 ? fileCount + ' {{ __('messages.files_selected') ?? 'files selected' }}' : '{{ __('messages.select_multiple_files') }}'"
                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center truncate w-full"></span>
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