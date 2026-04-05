@extends('layouts.employee')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
            {{ __('messages.my_payslips') }}
        </h1>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800/50 border-b border-gray-800">
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.select_month') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.basic_salary') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.net_salary') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300 text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($payslips as $payslip)
                    <tr class="hover:bg-gray-800/30 transition-colors cursor-pointer" onclick="window.location='{{ route('employee.payslips.show', $payslip->id) }}'">
                        <td class="p-4">
                            <span class="text-white font-medium">{{ $payslip->payrollRun->month->format('F Y') }}</span>
                        </td>
                        <td class="p-4 text-gray-300 font-mono">
                            {{ number_format($payslip->basic_salary, 2) }}
                        </td>
                        <td class="p-4 text-blue-400 font-bold font-mono">
                            {{ number_format($payslip->net_salary, 2) }}
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('employee.payslips.show', $payslip->id) }}" class="text-blue-400 hover:text-blue-300 transition-colors text-sm">
                                {{ __('messages.view') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            {{ __('messages.no_payslips') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payslips->hasPages())
        <div class="mt-4">
            {{ $payslips->links() }}
        </div>
    @endif
</div>
@endsection
