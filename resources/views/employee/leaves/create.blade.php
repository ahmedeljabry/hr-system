@extends('layouts.employee')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-10">
        <div class="bg-secondary rounded-3xl shadow-xl p-8 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <h1 class="text-3xl font-black mb-2 tracking-tight">{{ __('Request Leave') }}</h1>
                <p class="text-primary/70 opacity-90 text-sm md:text-base">{{ __('Submit a new leave request for approval.') }}</p>
            </div>
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 text-white opacity-10 group-hover:scale-110 transition-transform duration-700">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-red-50 border border-red-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-red-800 font-bold tracking-tight">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Balance Summary Grid -->
    @if(count($balanceSummary) > 0)
    <div class="mb-8 grid grid-cols-2 sm:grid-cols-3 gap-4">
        @foreach($balanceSummary as $balance)
            <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 truncate">{{ $balance['type']->name }}</p>
                <p class="text-xl font-black text-secondary leading-none">{{ $balance['remaining'] }} <span class="text-[10px] font-bold text-gray-400 lowercase">{{ __('left') }}</span></p>
            </div>
        @endforeach
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 p-10 overflow-hidden relative">
        <!-- Background accents -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-bl-[5rem] -mr-10 -mt-10"></div>
        
        <form action="{{ route('employee.leaves.store') }}" method="POST" class="space-y-8 relative z-10">
            @csrf

            <div class="space-y-2">
                <label for="leave_type_id" class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 block ml-1">{{ __('Leave Type') }} <span class="text-rose-500">*</span></label>
                <div class="relative group">
                    <select name="leave_type_id" id="leave_type_id" required
                        class="block w-full rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 transition-all duration-300 text-sm font-bold text-secondary px-5 py-4 outline-none appearance-none">
                        <option value="">{{ __('Select leave type...') }}</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->max_days_per_year > 0 ? $type->max_days_per_year . ' ' . __('days / year') : __('Unlimited') }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
                @error('leave_type_id') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 tracking-tight">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="start_date" class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 block ml-1">{{ __('Start Date') }} <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                        class="block w-full rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 transition-all duration-300 text-sm font-bold text-secondary px-5 py-4 outline-none transition-all" value="{{ old('start_date') }}">
                    @error('start_date') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="end_date" class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 block ml-1">{{ __('End Date') }} <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                        class="block w-full rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 transition-all duration-300 text-sm font-bold text-secondary px-5 py-4 outline-none transition-all" value="{{ old('end_date') }}">
                    @error('end_date') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 tracking-tight">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="reason" class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 block ml-1">{{ __('Reason') }}</label>
                <textarea name="reason" id="reason" rows="4"
                    class="block w-full rounded-2xl border-2 border-gray-50 bg-gray-50/50 focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/5 transition-all duration-300 text-sm font-bold text-secondary px-5 py-4 outline-none resize-none" 
                    placeholder="{{ __('Optional reason for your leave request...') }}">{{ old('reason') }}</textarea>
                @error('reason') <p class="text-rose-500 text-[10px] font-bold mt-1 ml-1 tracking-tight">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-10 border-t border-gray-50">
                <a href="{{ route('employee.leaves.index') }}" class="text-sm font-black uppercase tracking-widest text-gray-400 hover:text-secondary transition-colors">{{ __('Cancel') }}</a>
                <button type="submit" class="inline-flex items-center px-10 py-4 bg-primary hover:bg-primary/90 text-secondary text-sm font-black rounded-2xl shadow-xl shadow-primary/20 transition-all duration-300 hover:-translate-y-1 active:scale-95">
                    <svg class="w-5 h-5 mr-3 {{ app()->getLocale() == 'ar' ? 'ml-3 mr-0' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    {{ __('Submit Request') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
