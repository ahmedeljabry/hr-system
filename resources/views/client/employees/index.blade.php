@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12" x-data="{ viewMode: localStorage.getItem('employee_view_mode') || 'grid' }" @view-changed.window="viewMode = $event.detail">
    <div class="w-full">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.employees')" 
            :subtitle="__('messages.total_employees') . ': ' . $employees->total()"
        >
            <x-slot name="actions">
                @if(Route::has('client.employees.import.form'))
                <a href="{{ route('client.employees.import.form') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-bold rounded-xl transition-all duration-300 backdrop-blur-md group/btn">
                    <svg class="w-4 h-4 me-2 text-primary group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    {{ __('messages.import_employees') }}
                </a>
                @endif

                <a href="{{ route('client.employees.terminated') }}" 
                   class="inline-flex items-center px-6 py-3 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-200 text-xs font-black rounded-xl transition-all duration-300 backdrop-blur-md">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                    </svg>
                    {{ __('messages.terminated_employees') }}
                </a>

                <a href="{{ route('client.employees.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/add">
                    <svg class="w-4 h-4 me-2 group-hover/add:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('messages.add_employee') }}
                </a>
            </x-slot>
        </x-dashboard-sub-header>


        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-100 p-5 rounded-2xl shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-green-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-100 p-5 rounded-2xl shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-red-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="space-y-8">
            
            <!-- Main Content Container -->
            <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden min-h-[600px] flex flex-col transition-all duration-500">
                
                <!-- Search & Layout Controls -->
                <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row items-center justify-between gap-6 bg-gray-50/30">
                    <form method="GET" action="{{ route('client.employees.index') }}" class="relative w-full max-w-xl group">
                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-6' : 'left-0 pl-6' }} flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full {{ app()->getLocale() == 'ar' ? 'pr-14 pl-10' : 'pl-14 pr-10' }} py-4 text-sm font-medium border-2 border-transparent bg-white rounded-2xl shadow-sm focus:ring-0 focus:border-primary/30 focus:bg-white transition-all placeholder:text-gray-300" 
                               placeholder="{{ __('messages.search_employees') }}">
                        
                        @if(request('search'))
                            <button type="button" @click="window.location.href='{{ route('client.employees.index') }}'" class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} flex items-center text-gray-300 hover:text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </form>

                    <div class="flex items-center gap-2 p-1.5 bg-gray-100/80 rounded-2xl border border-gray-200/50">
                        <button type="button" @click="viewMode = 'grid'; localStorage.setItem('employee_view_mode', 'grid')" 
                           :class="viewMode === 'grid' ? 'bg-white text-secondary shadow-sm' : 'text-gray-400 hover:text-secondary'"
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                            {{ __('messages.grid') }}
                        </button>
                        <button type="button" @click="viewMode = 'list'; localStorage.setItem('employee_view_mode', 'list')" 
                           :class="viewMode === 'list' ? 'bg-white text-secondary shadow-sm' : 'text-gray-400 hover:text-secondary'"
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
                            {{ __('messages.list') }}
                        </button>
                    </div>
                </div>

                <!-- Views -->
                <div class="flex-grow">
                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" x-cloak class="p-8 animate-in fade-in duration-500">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">
                            @forelse($employees as $employee)
                                @include('client.employees._grid-card', ['employee' => $employee])
                            @empty
                                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center opacity-60">
                                    <div class="bg-gray-100 p-8 rounded-[2.5rem] mb-6">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-secondary mb-2">{{ __('messages.no_employees') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_employees_desc') ?? 'Start by adding your first employee to the system.' }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- List View -->
                    <div x-show="viewMode === 'list'" x-cloak class="overflow-x-auto p-1 animate-in fade-in duration-500">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee_name') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.position') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.nationality') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.national_id_number') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.basic_salary') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.hire_date') }}</th>
                                    <th class="px-8 py-5 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($employees as $employee)
                                    @include('client.employees._list-row', ['employee' => $employee])
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-8 py-32 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-40">
                                                <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                <span class="text-lg font-bold text-gray-500">{{ __('messages.no_employees') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer / Pagination -->
                @if($employees->hasPages())
                    <div class="p-8 border-t border-gray-50 bg-gray-50/20">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('submit', function(e) {
        console.log('Form submission detected:', e.target.action);
    });
</script>
@endpush
