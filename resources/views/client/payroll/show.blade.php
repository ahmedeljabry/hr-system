@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
                {{ __('messages.payroll') }} - {{ $run->month->format('F Y') }}
            </h1>
            <div class="mt-2 text-sm">
                <span class="text-gray-400">{{ __('messages.status') }}:</span>
                @if($run->isConfirmed())
                    <span class="text-green-400 bg-green-500/10 px-2 py-1 rounded">{{ __('messages.payroll_confirmed') }}</span>
                @else
                    <span class="text-yellow-400 bg-yellow-500/10 px-2 py-1 rounded">{{ __('messages.payroll_draft') }}</span>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            @if($run->isDraft())
                <form method="POST" action="{{ route('client.payroll.confirm', $run->id) }}" class="inline-block border">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 shadow-lg shadow-green-500/30">
                        {{ __('messages.confirm_payroll') }}
                    </button>
                </form>
            @endif
            <a href="{{ route('client.payroll.index') }}" class="text-gray-400 hover:text-white transition-colors duration-200">
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <table class="w-full text-left flex border-collapse">
            <thead>
                <tr class="bg-gray-800/50 border-b border-gray-800">
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.employee_name') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.basic_salary') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.total_allowances') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.total_deductions') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.net_salary') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @php $totalNet = 0; @endphp
                @foreach($run->payslips as $payslip)
                    @php $totalNet += $payslip->net_salary; @endphp
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 text-gray-300">{{ $payslip->employee->name }}</td>
                        <td class="p-4 text-gray-300 font-mono">{{ number_format($payslip->basic_salary, 2) }}</td>
                        <td class="p-4 text-green-400 font-mono">+{{ number_format($payslip->total_allowances, 2) }}</td>
                        <td class="p-4 text-red-400 font-mono">-{{ number_format($payslip->total_deductions, 2) }}</td>
                        <td class="p-4 font-bold text-blue-400 font-mono">{{ number_format($payslip->net_salary, 2) }}</td>
                    </tr>
                @endforeach
                
                @if($run->payslips->isEmpty())
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">
                            {{ __('messages.no_payslips') }}
                        </td>
                    </tr>
                @endif
            </tbody>
            @if($run->payslips->isNotEmpty())
            <tfoot class="bg-gray-800/50 border-t border-gray-800">
                <tr>
                    <td colspan="4" class="p-4 text-right font-semibold text-gray-300">{{ __('messages.total_net_payout') }}:</td>
                    <td class="p-4 font-bold text-indigo-400 font-mono">{{ number_format($totalNet, 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

</div>
@endsection
