@extends('layouts.employee')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="no-print flex justify-between items-center bg-white border border-gray-100 p-6 rounded-2xl shadow-sm mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ __('messages.payslip_detail') }}
            </h1>
            <p class="text-gray-500 mt-1">{{ $payslip->payrollRun->month->format('F Y') }}</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 rtl:ml-2 ltr:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                {{ __('messages.print') ?? 'Print' }}
            </button>
            <a href="{{ route('employee.payslips.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-500 hover:text-gray-900 transition-colors duration-200">
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Embedded Print Receipt Component -->
    <x-payslip-receipt :payslip="$payslip" />
</div>
@endsection
