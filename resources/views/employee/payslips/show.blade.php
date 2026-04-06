@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <!-- Standard Header -->
    <x-dashboard-sub-header 
        class="no-print"
        :title="__('messages.payslip_detail')" 
        :subtitle="$payslip->payrollRun->month->translatedFormat('F Y')"
        :backLink="route('employee.payslips.index')"
    >
        <x-slot name="actions">
            <button onclick="window.print()" class="group flex items-center gap-3 bg-primary px-6 py-3 rounded-xl font-black text-secondary hover:bg-primary/90 transition-all duration-300 shadow-xl shadow-primary/10">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                {{ __('messages.print') }}
            </button>
        </x-slot>
    </x-dashboard-sub-header>


    <!-- Embedded Print Receipt Component -->
    <div class="w-full">
        <x-payslip-receipt :payslip="$payslip" />
    </div>
</div>
@endsection
