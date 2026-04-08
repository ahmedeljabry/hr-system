@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.terminated_employees')" 
            :subtitle="__('messages.total_terminated') . ': ' . $employees->total()"
            :backLink="route('client.employees.index')"
        >
        </x-dashboard-sub-header>


        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-100 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="bg-green-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="space-y-8">
            
            <!-- Main Content Container -->
            <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden min-h-[600px] flex flex-col transition-all duration-500">
                
                <!-- Search -->
                <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row items-center justify-between gap-6 bg-gray-50/30">
                    <form method="GET" action="{{ route('client.employees.terminated') }}" class="relative w-full max-w-xl group">
                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-6' : 'left-0 pl-6' }} flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full {{ app()->getLocale() == 'ar' ? 'pr-14 pl-10' : 'pl-14 pr-10' }} py-4 text-sm font-medium border-2 border-transparent bg-white rounded-2xl shadow-sm focus:ring-0 focus:border-primary/30 focus:bg-white transition-all placeholder:text-gray-300" 
                               placeholder="{{ __('messages.search_terminated_employees') }}">
                    </form>
                </div>

                <!-- List View -->
                <div class="flex-grow">
                    <div class="overflow-x-auto p-1">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.employee_name') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.position') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.termination_reason') }}</th>
                                    <th class="px-8 py-5 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.terminated_at') }}</th>
                                    <th class="px-8 py-5 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($employees as $employee)
                                    <tr class="hover:bg-gray-50/50 transition-all duration-300">
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <x-avatar :name="$employee->name" size="md" class="rounded-2xl shadow-sm border border-gray-100 grayscale" />
                                                <div>
                                                    <div class="text-base font-black text-secondary tracking-tight capitalize">{{ $employee->name }}</div>
                                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $employee->position }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-500 bg-gray-50 inline-block px-3 py-1 rounded-xl border border-gray-100">{{ $employee->position }}</div>
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            @if($employee->termination)
                                                <div class="text-sm font-bold text-red-600">{{ \App\Enums\TerminationReason::from((int) $employee->termination->reason_case)->label() }}</div>
                                            @else
                                                <span class="text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-500">{{ $employee->termination ? $employee->termination->terminated_at->format('d/m/Y') : '—' }}</div>
                                        </td>
                                        <td class="px-8 py-6 text-end">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('client.employees.show', $employee) }}" 
                                                   class="inline-flex items-center gap-2 px-3 py-1.5 text-secondary hover:bg-secondary/5 rounded-xl transition-all duration-300 border border-gray-100 font-bold text-xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    <span>{{ __('messages.view_history') }}</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-32 text-center text-gray-400 font-bold">
                                            {{ __('messages.no_terminated_employees') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
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
