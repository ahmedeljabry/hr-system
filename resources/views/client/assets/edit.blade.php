@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.edit_component') ?? __('Edit Asset') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('Update asset details and custody information.') }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.assets.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold rounded-2xl transition-all duration-300 backdrop-blur-md group/back">
                        <svg class="w-5 h-5 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('messages.back') }}
                    </a>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

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
            <form method="POST" action="{{ route('client.assets.update', $asset) }}" class="p-12 space-y-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Asset Type/Name -->
                    <div class="space-y-3">
                        <label for="type" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.asset_type') ?? __('Property Type') }} <span class="text-primary">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-6 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <input type="text" name="type" id="type" value="{{ old('type', $asset->type) }}" required
                                   class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 ps-14 pe-6 text-secondary font-bold transition-all duration-300 outline-none"
                                   placeholder="e.g. MacBook Pro M3">
                        </div>
                    </div>

                    <!-- Serial Number -->
                    <div class="space-y-3">
                        <label for="serial_number" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.serial_number') ?? __('Serial Number') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-6 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                            </div>
                            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                                   class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 ps-14 pe-6 text-secondary font-mono font-bold transition-all duration-300 outline-none"
                                   placeholder="Unique Device ID">
                        </div>
                    </div>

                    <!-- Custodian -->
                    <div class="md:col-span-2 space-y-3">
                        <label for="employee_id" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.assigned_employee') ?? __('Custodian') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-6 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <select name="employee_id" id="employee_id"
                                    class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 ps-14 pe-6 text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                                <option value="">{{ __('messages.inventory') ?? __('Inventory (Company)') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $asset->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Assignment Date -->
                    <div class="space-y-3">
                        <label for="assigned_date" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.purchase_date') ?? __('Assignment Date') }} <span class="text-primary">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-6 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" name="assigned_date" id="assigned_date" value="{{ old('assigned_date', $asset->assigned_date->format('Y-m-d')) }}" required
                                   class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 ps-14 pe-6 text-secondary font-bold transition-all duration-300 outline-none">
                        </div>
                    </div>

                    <!-- Return Date -->
                    <div class="space-y-3">
                        <label for="returned_date" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('Return Date') }}</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 ps-6 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" name="returned_date" id="returned_date" value="{{ old('returned_date', $asset->returned_date?->format('Y-m-d')) }}"
                                   class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 ps-14 pe-6 text-secondary font-bold transition-all duration-300 outline-none">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2 space-y-3">
                        <label for="description" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.assets_desc') ?? __('Asset Details') }}</label>
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-3xl py-4 px-6 text-secondary font-medium transition-all duration-300 outline-none"
                                  placeholder="Provide conditions, configuration, or other details...">{{ old('description', $asset->description) }}</textarea>
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
                        {{ __('messages.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
