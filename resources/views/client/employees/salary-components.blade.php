@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.salary_components') . ': ' . $employee->name" 
            :subtitle="$employee->position"
            :backLink="route('client.employees.show', $employee->id)"
        />

        @if(session('success'))
            <div class="animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Quick Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 group-hover:text-secondary transition-colors">{{ __('messages.basic_salary') }}</p>
                <p class="text-3xl font-black text-secondary">{{ number_format($employee->basic_salary, 2) }} <span class="text-xs text-gray-300 uppercase ms-1">{{ __('messages.currency_sar') }}</span></p>
            </div>
            
            <div class="bg-white rounded-3xl p-8 border border-emerald-100 shadow-sm hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-black text-emerald-600/60 uppercase tracking-widest mb-2">{{ __('messages.total_allowances') }}</p>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <p class="text-3xl font-black text-emerald-600 tracking-tight">+{{ number_format($employee->salaryComponents->where('type', 'allowance')->sum('amount'), 2) }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-red-100 shadow-sm hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-black text-red-400/60 uppercase tracking-widest mb-2">{{ __('messages.total_deductions') }}</p>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></div>
                    <p class="text-3xl font-black text-red-500 tracking-tight">-{{ number_format($employee->salaryComponents->where('type', 'deduction')->sum('amount'), 2) }}</p>
                </div>
            </div>

            <div class="bg-primary rounded-3xl p-8 border border-primary shadow-[0_15px_40px_rgba(var(--color-primary-rgb),0.2)] group relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-secondary/60 uppercase tracking-widest mb-2">{{ __('messages.net_salary') }}</p>
                    <p class="text-3xl font-black text-secondary">{{ number_format($employee->basic_salary + $employee->salaryComponents->where('type', 'allowance')->sum('amount') - $employee->salaryComponents->where('type', 'deduction')->sum('amount'), 2) }}</p>
                </div>
                <div class="absolute -bottom-4 -right-4 w-20 h-20 text-white/10 group-hover:scale-150 transition-transform duration-700">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-1.84-.23-3.32-1.33-3.41-3.13h2.32c.11.83.74 1.25 1.56 1.25.96 0 1.54-.48 1.54-1.25 0-.61-.31-1.07-1.84-1.44-1.99-.48-3.41-1.12-3.41-3.21 0-1.72 1.34-2.84 3.13-3.08V5h2.82v1.94c1.55.22 2.87 1.15 2.99 2.94h-2.28c-.09-.72-.59-1.15-1.46-1.15-.84 0-1.42.41-1.42 1.1 0 .61.56.93 1.95 1.33 1.99.56 3.32 1.3 3.32 3.25-.01 1.95-1.57 2.96-3.35 3.08z"/></svg>
                </div>
            </div>
        </div>

        <!-- Add New Component Form Card -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 p-10 transition-all duration-500">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.add_component') }}</h3>
            </div>

            <form method="POST" action="{{ route('client.salary-components.store', $employee->id) }}" class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end">
                @csrf
                
                <div class="space-y-3">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.type') }} <span class="text-primary">*</span></label>
                    <select name="type" required class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 px-6 text-secondary font-bold transition-all duration-300 outline-none appearance-none">
                        <option value="allowance">{{ __('messages.allowance') }}</option>
                        <option value="deduction">{{ __('messages.deduction') }}</option>
                    </select>
                </div>

                <div class="space-y-3 col-span-1 md:col-span-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.component_name') }} <span class="text-primary">*</span></label>
                    <input type="text" name="name" required placeholder="{{ __('e.g. Housing Allowance, Late Penalty') }}"
                           class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 px-6 text-secondary font-bold transition-all duration-300 outline-none">
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.amount') }} <span class="text-primary">*</span></label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00"
                           class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl py-4 px-6 text-secondary font-black transition-all duration-300 outline-none">
                </div>

                <div class="md:col-span-4 flex justify-center pt-4">
                    <button type="submit" 
                            class="inline-flex items-center px-16 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit">
                        <svg class="w-7 h-7 me-4 group-hover/submit:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        {{ __('messages.add_component') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Components Table Section -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
            <div class="px-10 py-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h3 class="text-lg font-black text-secondary tracking-tight uppercase tracking-widest">{{ __('Active Components') }}</h3>
                <span class="px-4 py-1.5 bg-secondary text-primary text-[10px] font-black rounded-full uppercase tracking-tighter">{{ $employee->salaryComponents->count() }} {{ __('Total Items') }}</span>
            </div>

            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                        <th class="px-10 py-6">{{ __('messages.component_name') }}</th>
                        <th class="px-10 py-6">{{ __('messages.type') }}</th>
                        <th class="px-10 py-6">{{ __('messages.amount') }}</th>
                        <th class="px-10 py-6 text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($employee->salaryComponents as $component)
                        <tr x-data="{ editing: false }" class="group hover:bg-gray-50/80 transition-all duration-300">
                            <!-- Display Mode -->
                            <td x-show="!editing" class="px-10 py-6">
                                <span class="text-base font-bold text-secondary">{{ $component->name }}</span>
                            </td>
                            <td x-show="!editing" class="px-10 py-6">
                                @if($component->type === 'allowance')
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-black tracking-tight border border-emerald-100">
                                        <svg class="w-3.5 h-3.5 me-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>
                                        {{ __('messages.allowance') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-red-50 text-red-500 text-xs font-black tracking-tight border border-red-100">
                                        <svg class="w-3.5 h-3.5 me-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                        {{ __('messages.deduction') }}
                                    </span>
                                @endif
                            </td>
                            <td x-show="!editing" class="px-10 py-6">
                                <span class="text-lg font-black {{ $component->type === 'allowance' ? 'text-emerald-600' : 'text-red-500' }}">{{ number_format($component->amount, 2) }}</span>
                            </td>

                            <!-- Edit Mode Form -->
                            <td x-show="editing" colspan="3" class="px-10 py-6 bg-primary/5">
                                <form method="POST" action="{{ route('client.salary-components.update', [$employee->id, $component->id]) }}" class="flex items-center gap-4">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $component->name }}" class="flex-1 bg-white border-2 border-primary/20 focus:border-primary outline-none text-secondary font-bold rounded-xl px-4 py-3" required>
                                    <select name="type" class="w-40 bg-white border-2 border-primary/20 focus:border-primary outline-none text-secondary font-bold rounded-xl px-4 py-3" required>
                                        <option value="allowance" {{ $component->type === 'allowance' ? 'selected' : '' }}>{{ __('messages.allowance') }}</option>
                                        <option value="deduction" {{ $component->type === 'deduction' ? 'selected' : '' }}>{{ __('messages.deduction') }}</option>
                                    </select>
                                    <input type="number" step="0.01" name="amount" value="{{ $component->amount }}" class="w-40 bg-white border-2 border-primary/20 focus:border-primary outline-none text-secondary font-black rounded-xl px-4 py-3" required>
                                    
                                    <div class="flex items-center gap-2 ps-4 border-s border-primary/10">
                                        <button type="submit" class="p-3 bg-emerald-500 text-white rounded-2xl hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all active:scale-95" title="{{ __('Save') }}">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                        <button type="button" @click="editing = false" class="p-3 bg-gray-100 text-gray-500 rounded-2xl hover:bg-gray-200 transition-all active:scale-95" title="{{ __('Cancel') }}">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            
                            <td x-show="!editing" class="px-10 py-6 text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" @click="editing = true" class="p-3 text-gray-400 hover:text-primary hover:bg-primary/10 rounded-2xl transition-all duration-300" title="{{ __('Edit') }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    
                                    <form method="POST" action="{{ route('client.salary-components.destroy', [$employee->id, $component->id]) }}" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-3 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all duration-300" title="{{ __('Delete') }}">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    
                    @if($employee->salaryComponents->isEmpty())
                        <tr>
                            <td colspan="4" class="px-10 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-1c.51-.418.815-1.002.815-1.599 0-1.105-1.343-2-3-2s-3 .895-3 2 1.343 2 3 2m.599 1.599c-.51.418-.815 1.002-.815 1.599 0 1.105 1.343 2 3 2s3-.895 3-2-1.343-2-3-2m-3.599 1.599c.51.418.815 1.002.815 1.599"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-bold uppercase tracking-[0.2em] text-xs">{{ __('No components configured yet.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
