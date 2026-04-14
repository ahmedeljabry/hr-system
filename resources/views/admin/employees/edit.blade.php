@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-7xl mx-auto">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.edit_employee')" 
            :subtitle="$employee->name . ' - ' . $employee->client->name"
            :backLink="route('admin.employees.index')"
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

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
            <form method="POST" action="{{ route('admin.employees.update', $employee->id) }}" class="p-10 space-y-12">
                @csrf
                @method('PUT')

                <!-- Section: Personal & Account Information -->
                <div class="space-y-10">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        <h2 class="text-xl font-black text-secondary tracking-tight uppercase tracking-wider">
                            {{ __('messages.personal_account_information') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Arabic Name -->
                        <div class="space-y-3">
                            <label for="name_ar" class="block text-[10px] font-black text-gray-400 gap-2 uppercase tracking-widest">{{ __('messages.name_ar') }} <span class="text-primary">*</span></label>
                            <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $employee->name_ar) }}" required
                                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>

                        <!-- English Name -->
                        <div class="space-y-3">
                            <label for="name_en" class="block text-[10px] font-black text-gray-400 gap-2 uppercase tracking-widest">{{ __('messages.name_en') }} <span class="text-primary">*</span></label>
                            <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $employee->name_en) }}" required
                                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>

                        <!-- Email -->
                        <div class="space-y-3">
                            <label for="email" class="block text-[10px] font-black text-gray-400 gap-2 uppercase tracking-widest">{{ __('messages.email') }} <span class="text-primary">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required
                                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>

                        <!-- Phone -->
                        <div class="space-y-3">
                            <label for="phone" class="block text-[10px] font-black text-gray-400 gap-2 uppercase tracking-widest">{{ __('messages.phone_field') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}"
                                class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>

                        @php
                            $nationalitiesList = ['Saudi', 'Egyptian', 'Yemeni', 'Jordanian', 'Syrian', 'Sudanese', 'Palestinian', 'Lebanese', 'Moroccan', 'Tunisian', 'Algerian', 'Indian', 'Pakistani', 'Bangladeshi', 'Filipino', 'Afghan', 'Indonesian', 'Nepalese', 'Sri Lankan', 'Ethiopian'];
                            $currentNationality = old('nationality', $employee->nationality ?? 'Saudi');
                            $isOther = !in_array($currentNationality, $nationalitiesList);
                        @endphp
                        
                        <!-- Gender -->
                        <div class="space-y-3">
                            <label for="gender" class="block text-[10px] font-black text-gray-400 gap-2 uppercase tracking-widest">{{ __('messages.gender') }} <span class="text-primary">*</span></label>
                            <select name="gender" id="gender" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl outline-none font-bold text-secondary transition-all appearance-none">
                                <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                            </select>
                        </div>

                        <!-- Nationality -->
                        <div class="space-y-3" x-data="{ nationality: '{{ $isOther ? 'Other' : $currentNationality }}', otherNationality: '{{ $isOther ? $currentNationality : '' }}' }">
                            <label for="nationality_select" class="block text-[10px] font-black text-gray-400 gap-2 uppercase tracking-widest">{{ __('messages.nationality') }} <span class="text-primary">*</span></label>
                            <select id="nationality_select" required x-model="nationality" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl outline-none font-bold text-secondary transition-all appearance-none">
                                @foreach($nationalitiesList as $nat)
                                    <option value="{{ $nat }}">{{ $nat }}</option>
                                @endforeach
                                <option value="Other">{{ __('messages.other') }}</option>
                            </select>
                            <input type="hidden" name="nationality" :value="nationality === 'Other' ? otherNationality : nationality">
                            
                            <div x-show="nationality === 'Other'" class="mt-4">
                                <input type="text" x-model="otherNationality" class="w-full px-6 py-4 bg-amber-50 border-2 border-amber-100 rounded-2xl outline-none font-bold text-secondary" placeholder="{{ __('messages.other_nationality') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Employment & Financial -->
                <div class="space-y-10">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                        <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                        <h2 class="text-xl font-black text-secondary tracking-tight uppercase tracking-wider">
                            {{ __('messages.employment_details') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="space-y-3">
                            <label for="position" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.position') }} *</label>
                            <input type="text" name="position" id="position" value="{{ old('position', $employee->position) }}" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>
                        <div class="space-y-3">
                            <label for="official_job_title" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.official_job_title') }} *</label>
                            <input type="text" name="official_job_title" id="official_job_title" value="{{ old('official_job_title', $employee->official_job_title) }}" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>
                        <div class="space-y-3">
                            <label for="hire_date" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.hire_date') }} *</label>
                            <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>
                        <div class="space-y-3">
                            <label for="annual_leave_days" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.annual_leave_days') }} *</label>
                            <input type="number" name="annual_leave_days" id="annual_leave_days" value="{{ old('annual_leave_days', $employee->annual_leave_days) }}" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary rounded-2xl outline-none font-bold text-secondary transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.basic_salary') }}</label>
                            <input type="number" step="0.01" name="basic_salary" value="{{ old('basic_salary', $employee->basic_salary) }}" class="w-full p-4 rounded-xl border-none font-black text-secondary">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.housing_allowance') }}</label>
                            <input type="number" step="0.01" name="housing_allowance" value="{{ old('housing_allowance', $employee->housing_allowance) }}" class="w-full p-4 rounded-xl border-none font-black text-secondary">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.transportation_allowance') }}</label>
                            <input type="number" step="0.01" name="transportation_allowance" value="{{ old('transportation_allowance', $employee->transportation_allowance) }}" class="w-full p-4 rounded-xl border-none font-black text-secondary">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.other_allowances') }}</label>
                            <input type="number" step="0.01" name="other_allowances" value="{{ old('other_allowances', $employee->other_allowances) }}" class="w-full p-4 rounded-xl border-none font-black text-secondary">
                        </div>
                    </div>
                </div>

                @if($employee->user)
                <!-- Section: Login Credentials -->
                <div class="space-y-10">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                        <div class="w-1.5 h-6 bg-red-400 rounded-full"></div>
                        <h2 class="text-xl font-black text-secondary tracking-tight uppercase tracking-wider">
                            {{ __('messages.password_change_optional') }}</h2>
                    </div>
                    <div class="max-w-md space-y-3">
                        <label for="password" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.new_password') }}</label>
                        <input type="password" name="password" id="password" class="w-full px-6 py-4 bg-red-50/30 border-2 border-transparent focus:border-red-200 rounded-2xl outline-none font-bold text-secondary transition-all" placeholder="{{ __('messages.leave_blank_to_keep_current') }}">
                    </div>
                </div>
                @endif

                <div class="pt-10 border-t border-gray-100 flex justify-end items-center gap-8">
                    <a href="{{ route('admin.employees.index') }}" class="text-xs font-black text-gray-400 uppercase tracking-widest hover:text-secondary transition-colors">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="group relative inline-flex items-center gap-4 px-12 py-5 bg-secondary text-white font-black rounded-2xl shadow-[0_15px_40px_rgba(20,37,51,0.2)] hover:translate-y-[-2px] transition-all duration-300">
                        <span class="text-xs uppercase tracking-widest">{{ __('messages.update') }}</span>
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
