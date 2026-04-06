@extends('layouts.employee')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <!-- Premium Hero Section -->
    <div class="bg-secondary overflow-hidden shadow-2xl rounded-[2.5rem] p-10 text-white mb-10 relative group border border-primary/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">
                    {{ __('messages.request_leave') }}</h1>
                <p class="text-gray-300 text-lg opacity-90">{{ __('messages.leave_request_desc') }}</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('employee.leaves.index') }}"
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

        <!-- Animated decorative overlays -->
        <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
    </div>

    @if(session('error') || $errors->any())
        <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-red-50 border border-red-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="text-red-800 text-sm font-bold tracking-tight">
                    @if(session('error'))
                        <p>{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Balance Summary Grid -->
    @if(count($balanceSummary) > 0)
    <div class="mb-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($balanceSummary as $balance)
            <div class="bg-white rounded-3xl p-6 text-center border border-gray-100 shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                @php
                    $typeKey = 'messages.' . strtolower(str_replace(' ', '_', $balance['type']->name));
                    $typeDisplayName = Lang::has($typeKey) ? __($typeKey) : $balance['type']->name;
                @endphp
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 transition-colors group-hover:text-primary">{{ $typeDisplayName }}</p>
                <div class="flex flex-col items-center">
                    <span class="text-3xl font-black text-secondary leading-none mb-1">{{ $balance['remaining'] }}</span>
                    <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ __('messages.left') }}</span>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-[3rem] shadow-[0_30px_70px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500 relative">
        <!-- Floating accents -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-primary/5 rounded-bl-[10rem] -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/5 rounded-tr-full -ml-8 -mb-8"></div>

        <form action="{{ route('employee.leaves.store') }}" method="POST" class="p-12 space-y-12 relative z-10">
            @csrf

            <!-- Form Body -->
            <div class="space-y-10">
                <!-- Leave Type Selection -->
                <div class="space-y-4">
                    <label for="leave_type_id" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('messages.leave_type') }} <span class="text-primary">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-4' : 'left-4' }} flex items-center pointer-events-none transition-all duration-300">
                            <div class="w-10 h-10 rounded-2xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                <svg class="w-5 h-5 text-primary/60 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <select name="leave_type_id" id="leave_type_id" required
                            class="block w-full bg-gray-50/50 border-2 border-transparent focus:border-primary/30 focus:bg-white focus:ring-8 focus:ring-primary/5 rounded-3xl py-5 {{ app()->getLocale() == 'ar' ? 'pr-18 pl-12' : 'pl-18 pr-12' }} text-secondary font-black transition-all duration-300 outline-none appearance-none cursor-pointer">
                            <option value="">{{ __('messages.select_leave_type') }}</option>
                            @foreach($leaveTypes as $type)
                                @php
                                    $typeItemKey = 'messages.' . strtolower(str_replace(' ', '_', $type->name));
                                    $typeItemName = Lang::has($typeItemKey) ? __($typeItemKey) : $type->name;
                                @endphp
                                <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $typeItemName }} ({{ $type->max_days_per_year > 0 ? $type->max_days_per_year . ' ' . __('messages.days_per_year') : __('messages.unlimited') }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Start Date -->
                    <div class="space-y-4">
                        <label for="start_date" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('messages.start_date') }} <span class="text-primary">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-4' : 'left-4' }} flex items-center pointer-events-none transition-all duration-300">
                                <div class="w-10 h-10 rounded-2xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="w-5 h-5 text-primary/60 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}"
                                class="block w-full bg-gray-50/50 border-2 border-transparent focus:border-primary/30 focus:bg-white focus:ring-8 focus:ring-primary/5 rounded-3xl py-5 {{ app()->getLocale() == 'ar' ? 'pr-18 pl-6' : 'pl-18 pr-6' }} text-secondary font-black transition-all duration-300 outline-none">
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="space-y-4">
                        <label for="end_date" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('messages.end_date') }} <span class="text-primary">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-4' : 'left-4' }} flex items-center pointer-events-none transition-all duration-300">
                                <div class="w-10 h-10 rounded-2xl bg-primary/10 group-focus-within:bg-primary/20 flex items-center justify-center transition-all duration-300 group-focus-within:scale-110">
                                    <svg class="w-5 h-5 text-primary/60 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}"
                                class="block w-full bg-gray-50/50 border-2 border-transparent focus:border-primary/30 focus:bg-white focus:ring-8 focus:ring-primary/5 rounded-3xl py-5 {{ app()->getLocale() == 'ar' ? 'pr-18 pl-6' : 'pl-18 pr-6' }} text-secondary font-black transition-all duration-300 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Reason Textarea -->
                <div class="space-y-4">
                    <label for="reason" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] ml-1">{{ __('messages.reason') }}</label>
                    <textarea name="reason" id="reason" rows="6"
                        class="block w-full bg-gray-50/50 border-2 border-transparent focus:border-primary/30 focus:bg-white focus:ring-8 focus:ring-primary/5 rounded-[2rem] py-6 px-8 text-secondary font-bold transition-all duration-300 outline-none resize-none"
                        placeholder="{{ __('messages.leave_reason_placeholder') }}">{{ old('reason') }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-10 border-t border-gray-50 flex items-center justify-center gap-10">
                <a href="{{ route('employee.leaves.index') }}"
                    class="px-8 py-4 text-gray-400 hover:text-secondary font-black uppercase tracking-widest text-xs transition-colors">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center px-16 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit">
                    <svg class="w-7 h-7 {{ app()->getLocale() == 'ar' ? 'ml-4' : 'mr-4' }} group-hover/submit:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('messages.submit_request') }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom icon padding class for RTL/LTR compatibility */
    .pr-18 { padding-right: 4.5rem !important; }
    .pl-18 { padding-left: 4.5rem !important; }
</style>
@endsection
