@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <h1 class="text-4xl font-extrabold tracking-tight text-primary">
                            {{ __('messages.payroll') }} - {{ $run->month->translatedFormat('F Y') }}
                        </h1>
                        <div class="px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest border {{ $run->isConfirmed() ? 'border-primary text-primary bg-primary/10' : 'border-yellow-400 text-yellow-400 bg-yellow-400/10' }}">
                            {{ $run->isConfirmed() ? __('messages.payroll_confirmed') : __('messages.payroll_draft') }}
                        </div>
                    </div>
                    <p class="text-gray-400 text-lg max-w-xl">
                        {{ __('messages.payroll_history_desc') }}
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-6">
                    <!-- Stat Item -->
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl flex flex-col items-center">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.total_net_payout') }}</span>
                        <span class="text-2xl font-black text-white">
                            <span class="text-primary text-sm me-1">$</span>{{ number_format($run->payslips->sum('net_salary'), 2) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($run->isDraft())
                            <form method="POST" action="{{ route('client.payroll.confirm', $run->id) }}" class="inline-block">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-8 py-3 bg-primary hover:bg-primary/90 text-secondary text-sm font-black rounded-2xl shadow-[0_10px_20px_rgba(var(--color-primary-rgb),0.2)] transition-all duration-300 hover:scale-105 group/confirm">
                                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    {{ __('messages.confirm_payroll') }}
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('client.payroll.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold rounded-2xl transition-all duration-300 backdrop-blur-md group/back">
                            <svg class="w-5 h-5 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-100 p-5 rounded-2xl shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-primary/20 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-primary" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <p class="text-sm font-bold text-gray-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden min-h-[500px] flex flex-col transition-all duration-500">
            <div class="overflow-x-auto p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.employee_name') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.basic_salary') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-green-600/70">{{ __('messages.total_allowances') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-red-600/70">{{ __('messages.total_deductions') }}</th>
                            <th class="px-8 py-6 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.net_salary') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($run->payslips as $payslip)
                            <tr class="group hover:bg-gray-50/50 transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-secondary/5 flex items-center justify-center font-black text-secondary group-hover:bg-primary group-hover:text-secondary transition-colors text-sm">
                                            {{ strtoupper(substr($payslip->employee->name, 0, 2)) }}
                                        </div>
                                        <span class="font-black text-secondary text-base">{{ $payslip->employee->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-gray-500 font-bold font-mono">{{ number_format($payslip->basic_salary, 2) }}</td>
                                <td class="px-8 py-6 text-green-600 font-black font-mono">
                                    <div class="inline-flex items-center">
                                        <span class="text-xs me-1">+</span>{{ number_format($payslip->total_allowances, 2) }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-red-600 font-black font-mono">
                                    <div class="inline-flex items-center">
                                        <span class="text-xs me-1">-</span>{{ number_format($payslip->total_deductions, 2) }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-end">
                                    <span class="bg-secondary/5 group-hover:bg-primary group-hover:text-secondary px-6 py-2 rounded-xl font-black text-secondary transition-all duration-300 font-mono text-lg">
                                        {{ number_format($payslip->net_salary, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-32 text-center text-gray-400 font-bold italic">
                                    {{ __('messages.no_payslips') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($run->payslips->isNotEmpty())
            <div class="p-10 border-t border-gray-50 bg-gray-50/20 mt-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 max-w-4xl mx-auto">
                    <div class="flex flex-col items-center md:items-start">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.employee_count') }}</span>
                        <span class="text-2xl font-black text-secondary">{{ $run->payslips->count() }}</span>
                    </div>
                    
                    <div class="w-px h-12 bg-gray-100 hidden md:block"></div>

                    <div class="flex flex-col items-center">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.total_allowances') }}</span>
                        <span class="text-2xl font-black text-green-600 font-mono">{{ number_format($run->payslips->sum('total_allowances'), 2) }}</span>
                    </div>

                    <div class="w-px h-12 bg-gray-100 hidden md:block"></div>

                    <div class="flex flex-col items-center">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.total_deductions') }}</span>
                        <span class="text-2xl font-black text-red-600 font-mono">{{ number_format($run->payslips->sum('total_deductions'), 2) }}</span>
                    </div>

                    <div class="w-px h-12 bg-gray-100 hidden md:block"></div>

                    <div class="flex flex-col items-center md:items-end">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-primary transition-colors">{{ __('messages.total_net_payout') }}</span>
                        <span class="text-3xl font-black text-primary bg-secondary px-8 py-3 rounded-2xl shadow-xl font-mono">
                            {{ number_format($run->payslips->sum('net_salary'), 2) }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
