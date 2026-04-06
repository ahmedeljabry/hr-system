@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        
        <!-- Premium Header -->
        <x-dashboard-sub-header 
            :title="__('messages.deductions')" 
            :subtitle="__('messages.deductions_desc')"
            :backLink="route('client.payroll.index')"
        >
            <x-slot name="leading">
                <div class="w-16 h-16 bg-rose-500/10 rounded-[1.5rem] flex items-center justify-center shrink-0 border border-rose-500/20 shadow-xl group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </x-slot>

            <x-slot name="actions">
                <a href="{{ route('client.deductions.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white text-xs font-black rounded-xl shadow-[0_10px_25px_rgba(244,63,94,0.3)] transition-all duration-300 hover:-translate-y-1 group/add">
                    <svg class="w-4 h-4 me-2 group-hover/add:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('messages.add_discount') }}
                </a>
            </x-slot>
        </x-dashboard-sub-header>

        <!-- Filter Bar -->
        <div class="mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <form id="filter-form" method="GET" action="{{ route('client.deductions.index') }}" class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative group min-w-[200px]">
                    <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-3' : 'left-3' }} flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <input type="month" name="month" value="{{ request('month') }}"
                        onchange="this.form.submit()"
                        class="block w-full bg-white border-2 border-transparent shadow-sm focus:border-primary/30 rounded-2xl py-3 {{ app()->getLocale() == 'ar' ? 'pr-12 pl-4' : 'pl-12 pr-4' }} text-sm font-bold text-secondary outline-none transition-all">
                </div>
                
                @if(request('month'))
                    <a href="{{ route('client.deductions.index') }}" 
                       class="text-xs font-black text-rose-500 hover:text-rose-600 transition-colors uppercase tracking-widest bg-rose-50 px-4 py-3 rounded-xl border border-rose-100 shadow-sm">
                        {{ __('messages.all_months') }}
                    </a>
                @endif
            </form>
        </div>

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

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
            <div class="overflow-x-auto p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.employee_name') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.period') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.discount_amount') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.notes') }}</th>
                            <th class="px-8 py-6 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($deductions as $deduction)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-black text-secondary tracking-tight">{{ $deduction->employee->name }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-500">{{ $deduction->deduction_date->format('M Y') }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-black text-rose-600 tracking-tight">{{ number_format($deduction->amount, 2) }} <span class="text-[10px] text-gray-300 uppercase ms-0.5">SAR</span></div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-bold text-gray-500 truncate max-w-xs">{{ $deduction->reason ?? '-' }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-end">
                                    <div class="flex justify-end gap-2">
                                        <form id="delete-deduction-{{ $deduction->id }}" action="{{ route('client.deductions.destroy', $deduction->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" 
                                                onclick="Swal.fire({
                                                    title: '{{ __('messages.are_you_sure') }}',
                                                    text: '{{ __('messages.delete_confirm', ['month' => $deduction->deduction_date->format('M Y')]) }}',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#f43f5e',
                                                    cancelButtonColor: '#0ea5e9',
                                                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                                                    cancelButtonText: '{{ __('messages.cancel') }}',
                                                    reverseButtons: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('delete-deduction-{{ $deduction->id }}').submit();
                                                    }
                                                })"
                                                class="p-2 text-rose-500 hover:text-white hover:bg-rose-500 border border-rose-500/10 hover:border-rose-500 rounded-xl transition-all duration-300" title="{{ __('messages.delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-32 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <div class="bg-gray-100 p-8 rounded-[2.5rem] mb-6">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-secondary mb-2">{{ __('messages.no_records_found') }}</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            @if($deductions->hasPages())
                <div class="p-8 border-t border-gray-50 bg-gray-50/20">
                    {{ $deductions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
