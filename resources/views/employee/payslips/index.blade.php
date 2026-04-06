@extends('layouts.employee')

@section('content')
<div class="space-y-10">
    <!-- Premium Hero Section -->
    <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white relative group border border-primary/20">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div class="space-y-4 text-center md:text-left {{ app()->getLocale() == 'ar' ? 'md:text-right' : '' }}">
                <h1 class="text-4xl font-extrabold tracking-tight text-primary">
                    {{ __('messages.my_payslips') }}
                </h1>
                <p class="text-gray-400 text-lg max-w-xl">
                    {{ __('messages.my_payslips_desc') }}
                </p>
            </div>
            
            <div class="hidden lg:block relative group-hover:scale-110 transition-transform duration-700">
                <div class="w-24 h-24 rounded-2xl bg-primary/20 flex items-center justify-center backdrop-blur-md border border-primary/30">
                    <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>
        
        <!-- Animated decorative overlays -->
        <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden min-h-[500px] flex flex-col transition-all duration-500">
        <div class="overflow-x-auto p-1">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.month') }} / {{ __('messages.year') }}</th>
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.basic_salary') }}</th>
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-emerald-600/70">{{ __('messages.housing_allowance') }}</th>
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-emerald-600/70">{{ __('messages.transportation_allowance') }}</th>
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-emerald-600/70">{{ __('messages.other_allowances') }}</th>
                        <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-red-600/70">{{ __('messages.total_deductions') }}</th>
                        <th class="px-8 py-6 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.total_salary') }}</th>
                        <th class="px-8 py-6 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-blue-600/70">{{ __('messages.net_salary') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payslips as $payslip)
                        <tr class="group hover:bg-gray-50/50 transition-all duration-300">
                            <td class="px-8 py-6">
                                <span class="font-black text-secondary text-base">{{ $payslip->payrollRun->month->translatedFormat('F Y') }}</span>
                            </td>
                            <td class="px-8 py-6 text-gray-500 font-bold font-mono">{{ number_format($payslip->basic_salary, 2) }}</td>
                            <td class="px-8 py-6 text-emerald-600 font-black font-mono">
                                <div class="inline-flex items-center">
                                    <span class="text-xs me-1">+</span>{{ number_format($payslip->housing_allowance, 2) }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-emerald-600 font-black font-mono">
                                <div class="inline-flex items-center">
                                    <span class="text-xs me-1">+</span>{{ number_format($payslip->transportation_allowance, 2) }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-emerald-600 font-black font-mono">
                                <div class="inline-flex items-center">
                                    <span class="text-xs me-1">+</span>{{ number_format($payslip->other_allowances, 2) }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-red-600 font-black font-mono">
                                <div class="inline-flex items-center">
                                    <span class="text-xs me-1">-</span>{{ number_format($payslip->total_deductions, 2) }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-end">
                                <span class="font-bold text-gray-600 font-mono">
                                    {{ number_format($payslip->basic_salary + $payslip->total_allowances, 2) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-end">
                                <a href="{{ route('employee.payslips.show', $payslip->id) }}" class="inline-block group/payout">
                                    <span class="bg-secondary/5 border border-transparent group-hover/payout:border-primary group-hover:bg-primary group-hover:text-secondary px-6 py-2 rounded-xl font-black text-secondary transition-all duration-300 font-mono text-lg flex items-center gap-3">
                                        {{ number_format($payslip->net_salary, 2) }}
                                        <svg class="w-4 h-4 opacity-0 group-hover/payout:opacity-100 group-hover/payout:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_payslips_found') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_payslips_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payslips->hasPages())
            <div class="p-10 border-t border-gray-50 bg-gray-50/20 mt-auto">
                {{ $payslips->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

