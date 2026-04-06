@extends('layouts.employee')

@section('content')
<div class="space-y-8">
    <div class="no-print bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100 p-8 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-secondary/5 flex items-center justify-center text-secondary">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-secondary tracking-tight">
                        {{ __('messages.payslip_detail') }}
                    </h1>
                    <p class="text-gray-500 mt-1">{{ $payslip->payrollRun->month->translatedFormat('F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="window.print()" class="group flex items-center gap-3 bg-secondary px-6 py-3 rounded-2xl font-black text-white hover:bg-primary hover:text-secondary transition-all duration-300 shadow-xl shadow-secondary/10">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    {{ __('messages.print') }}
                </button>
                <a href="{{ route('employee.payslips.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-50 text-gray-500 rounded-2xl font-black transition-all hover:bg-gray-100 uppercase tracking-widest text-xs">
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Embedded Print Receipt Component -->
    <div class="w-full">
        <x-payslip-receipt :payslip="$payslip" />
    </div>
</div>
@endsection
