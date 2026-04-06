@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <!-- Premium Header -->
    <x-dashboard-sub-header 
        :title="__('messages.my_deductions')" 
        :subtitle="__('messages.my_deductions_desc')"
    >
        <x-slot name="leading">
            <div class="w-16 h-16 bg-rose-500/10 rounded-[1.5rem] flex items-center justify-center shrink-0 border border-rose-500/20 shadow-xl group-hover:scale-110 transition-transform duration-500">
                <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </x-slot>
    </x-dashboard-sub-header>

    <!-- Main Card -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden min-h-[500px] flex flex-col transition-all duration-500">
        <div class="overflow-x-auto p-1">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.month') }} / {{ __('messages.year') }}</th>
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.deduction_reason') }}</th>
                        <th class="px-8 py-6 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.deduction_amount') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($deductions as $deduction)
                        <tr class="group hover:bg-gray-50/50 transition-all duration-300">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="font-black text-secondary text-base">{{ $deduction->deduction_date->format('M Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-gray-500">{{ $deduction->reason ?? '-' }}</div>
                            </td>
                            <td class="px-8 py-6 text-end whitespace-nowrap">
                                <span class="font-black text-rose-600 text-lg tracking-tight">
                                    {{ number_format($deduction->amount, 2) }}
                                    <span class="text-[10px] text-gray-300 uppercase ms-1">{{ __('messages.currency_sar') }}</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-12 h-12 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_deductions_found') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto text-center">{{ __('messages.no_deductions_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($deductions->hasPages())
            <div class="p-10 border-t border-gray-50 bg-gray-50/20 mt-auto">
                {{ $deductions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
