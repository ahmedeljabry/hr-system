@extends('layouts.employee')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
                {{ __('messages.payslip_detail') }}
            </h1>
            <p class="text-gray-400 mt-1">{{ $payslip->payrollRun->month->format('F Y') }}</p>
        </div>
        <a href="{{ route('employee.payslips.index') }}" class="text-gray-400 hover:text-white transition-colors duration-200">
            {{ __('messages.back') }}
        </a>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-xl overflow-hidden p-6 space-y-8">
        <!-- Header info -->
        <div class="flex justify-between items-end border-b border-gray-800 pb-6">
            <div>
                <h2 class="text-2xl font-semibold text-white">{{ $payslip->employee->name }}</h2>
                <p class="text-gray-400 mt-1">{{ $payslip->employee->position ?? 'Employee' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-400">{{ __('messages.basic_salary') }}</p>
                <p class="text-xl font-mono text-gray-200">{{ number_format($payslip->basic_salary, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Allowances -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-green-400 border-b border-gray-800 pb-2">Allowances</h3>
                <ul class="space-y-3">
                    @forelse($payslip->lineItems->where('type', 'allowance') as $item)
                        <li class="flex justify-between text-gray-300">
                            <span>{{ $item->component_name }}</span>
                            <span class="font-mono text-green-400">+{{ number_format($item->amount, 2) }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500 italic text-sm">No allowances</li>
                    @endforelse
                </ul>
                <div class="flex justify-between pt-2 border-t border-gray-800 font-medium">
                    <span class="text-gray-400">{{ __('messages.total_allowances') }}</span>
                    <span class="text-green-400 font-mono">{{ number_format($payslip->total_allowances, 2) }}</span>
                </div>
            </div>

            <!-- Deductions -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-red-400 border-b border-gray-800 pb-2">Deductions</h3>
                <ul class="space-y-3">
                    @forelse($payslip->lineItems->where('type', 'deduction') as $item)
                        <li class="flex justify-between text-gray-300">
                            <span>{{ $item->component_name }}</span>
                            <span class="font-mono text-red-400">-{{ number_format($item->amount, 2) }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500 italic text-sm">No deductions</li>
                    @endforelse
                </ul>
                <div class="flex justify-between pt-2 border-t border-gray-800 font-medium">
                    <span class="text-gray-400">{{ __('messages.total_deductions') }}</span>
                    <span class="text-red-400 font-mono">{{ number_format($payslip->total_deductions, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Net Salary -->
        <div class="bg-gray-800/50 rounded-xl p-6 mt-8 flex justify-between items-center border border-gray-700/50">
            <span class="text-xl font-medium text-gray-300">{{ __('messages.net_salary') }}</span>
            <span class="text-3xl font-bold text-blue-400 font-mono">{{ number_format($payslip->net_salary, 2) }}</span>
        </div>
    </div>
</div>
@endsection
