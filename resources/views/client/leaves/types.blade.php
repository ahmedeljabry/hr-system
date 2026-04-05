@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.leave_types_config') ?? __('Leave Types Configuration') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('messages.leave_types_desc') ?? 'Define and manage standard leave categories and annual allowances for your workforce.' }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.leaves.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold rounded-2xl transition-all duration-300 backdrop-blur-md group/back">
                        <svg class="w-5 h-5 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('messages.back_to_requests') ?? __('Back to Requests') }}
                    </a>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        @if(session('success'))
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Add Leave Type Form -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 p-10 sticky top-8 transition-all duration-500">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.add_leave_type') ?? __('Add Leave Type') }}</h3>
                    </div>

                    <form action="{{ route('client.leaves.store-type') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="space-y-3">
                            <label for="name" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.name') ?? __('Name') }} <span class="text-primary">*</span></label>
                            <input type="text" name="name" id="name" required placeholder="{{ __('e.g. Annual Leave') }}"
                                class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 px-6 text-secondary font-bold transition-all duration-300 outline-none @error('name') border-red-200 bg-red-50 @enderror" value="{{ old('name') }}">
                            @error('name') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <label for="max_days_per_year" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.max_days_per_year') ?? __('Max Days/Year') }} <span class="text-primary">*</span></label>
                                <span class="text-[10px] font-black text-primary/60 uppercase tracking-widest">{{ __('Unlimited = 0') }}</span>
                            </div>
                            <input type="number" name="max_days_per_year" id="max_days_per_year" required min="0" max="365"
                                class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 px-6 text-secondary font-black transition-all duration-300 outline-none" value="{{ old('max_days_per_year', 0) }}">
                            @error('max_days_per_year') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" 
                                class="inline-flex items-center px-10 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit mx-auto">
                            <svg class="w-7 h-7 me-4 group-hover/submit:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('messages.save') ?? __('Save') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Existing Leave Types -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.name') ?? __('Name') }}</th>
                                <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.max_days_per_year') ?? __('Max Allowance') }}</th>
                                <th class="px-8 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') ?? __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($leaveTypes as $type)
                                <tr class="hover:bg-gray-50/50 transition-all duration-300" x-data="{ editing: false }">
                                    <td class="px-8 py-6">
                                        <div x-show="!editing" class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-2xl bg-secondary/5 flex items-center justify-center text-secondary font-black">
                                                {{ substr(__($type->name), 0, 1) }}
                                            </div>
                                            <span class="text-base font-black text-secondary tracking-tight">{{ __($type->name) }}</span>
                                        </div>
                                        <template x-if="editing">
                                            <form action="{{ route('client.leaves.update-type', $type) }}" method="POST" class="flex items-center gap-4" id="edit-form-{{ $type->id }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="name" value="{{ $type->name }}" class="text-sm font-bold bg-gray-50 border-2 border-primary/20 focus:border-primary rounded-xl px-4 py-2 outline-none transition-all" required>
                                                <input type="number" name="max_days_per_year" value="{{ $type->max_days_per_year }}" class="text-sm font-black bg-gray-50 border-2 border-primary/20 focus:border-primary rounded-xl px-4 py-2 w-24 outline-none transition-all" required min="0">
                                                <div class="flex gap-2">
                                                    <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="{{ __('Save') }}">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                    </button>
                                                    <button type="button" @click="editing = false" class="p-2 text-gray-400 hover:bg-gray-100 rounded-xl transition-all" title="{{ __('Cancel') }}">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </div>
                                            </form>
                                        </template>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div x-show="!editing">
                                            @if($type->max_days_per_year > 0)
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-secondary/5 text-secondary text-xs font-black tracking-tight">
                                                    <svg class="w-3.5 h-3.5 me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                                                    {{ $type->max_days_per_year }} {{ __('Days') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-black tracking-tight uppercase">
                                                    {{ __('Unlimited') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                        <div x-show="!editing" class="flex items-center justify-end gap-3 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}">
                                            <button @click="editing = true" class="p-3 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-2xl transition-all duration-300" title="{{ __('Edit') }}">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            <form action="{{ route('client.leaves.destroy-type', $type) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-3 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all duration-300" title="{{ __('Delete') }}">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-gray-400 font-bold uppercase tracking-[0.2em] text-xs">{{ __('No leave types configured yet.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
