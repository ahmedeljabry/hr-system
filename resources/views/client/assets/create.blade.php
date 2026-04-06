@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-full mx-auto">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.add_asset')" 
            :subtitle="__('messages.assets')"
            :backLink="route('client.assets.index')"
        />


        @if ($errors->any())
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-red-50 border border-red-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
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
            <form method="POST" action="{{ route('client.assets.store') }}" class="p-12 space-y-16">
                @csrf

                <!-- Section: Asset Information -->
                <div class="space-y-10">
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-50">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.asset_information') ?? __('Asset Information') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Asset Type/Name -->
                        <div class="space-y-3">
                            <label for="type" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.asset_type') ?? __('Property Type') }} <span class="text-primary">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                    <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                        <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                </div>
                                <input type="text" name="type" id="type" value="{{ old('type') }}" required
                                       class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none"
                                       placeholder="{{ __('messages.asset_type_placeholder') }}">
                            </div>
                        </div>

                        <!-- Serial Number -->
                        <div class="space-y-3">
                            <label for="serial_number" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.serial_number') ?? __('Serial Number') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                    <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                        <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                    </div>
                                </div>
                                <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}"
                                       class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-mono font-bold transition-all duration-300 outline-none"
                                       placeholder="{{ __('messages.serial_number_placeholder') }}">
                            </div>
                        </div>

                        <!-- Custodian -->
                        <div class="md:col-span-2 space-y-3">
                            <label for="employee_id" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.assigned_employee') ?? __('Custodian') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                    <div class="w-8 h-8 rounded-xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                        <svg class="w-4 h-4 text-primary/60 group-focus-within:text-primary transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                </div>
                                <select name="employee_id" id="employee_id"
                                        class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                                    <option value="">{{ __('messages.inventory') ?? __('Inventory (Company)') }}</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Date -->
                        <div class="space-y-3">
                            <label for="assigned_date" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.purchase_date') ?? __('Assignment Date') }} <span class="text-primary">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                    <div class="w-8 h-8 rounded-xl bg-orange-50 group-focus-within:bg-orange-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                        <svg class="w-4 h-4 text-orange-400 group-focus-within:text-orange-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                </div>
                                <input type="date" name="assigned_date" id="assigned_date" value="{{ old('assigned_date', now()->format('Y-m-d')) }}" required
                                       class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
                            </div>
                        </div>

                        <!-- Return Date -->
                        <div class="space-y-3">
                            <label for="returned_date" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.return_date') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none transition-all duration-300">
                                    <div class="w-8 h-8 rounded-xl bg-rose-50 group-focus-within:bg-rose-100 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                        <svg class="w-4 h-4 text-rose-400 group-focus-within:text-rose-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                </div>
                                <input type="date" name="returned_date" id="returned_date" value="{{ old('returned_date') }}"
                                       class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 {{ app()->getLocale() == 'ar' ? 'pr-14 pl-6' : 'pl-14 pr-6' }} text-secondary font-bold transition-all duration-300 outline-none">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2 space-y-3">
                            <label for="description" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.assets_desc') ?? __('Asset Details') }}</label>
                            <textarea name="description" id="description" rows="4"
                                      class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-3xl py-4 px-6 text-secondary font-medium transition-all duration-300 outline-none"
                                      placeholder="{{ __('messages.asset_desc_placeholder') }}">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="pt-10 border-t border-gray-50 flex items-center justify-center gap-6">
                    <a href="{{ route('client.assets.index') }}" 
                       class="px-10 py-4 text-gray-500 hover:text-secondary font-black transition-colors">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-20 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit">
                        <svg class="w-7 h-7 me-4 group-hover/submit:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
