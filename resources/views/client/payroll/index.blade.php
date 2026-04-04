@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
            {{ __('messages.payroll_history') }}
        </h1>
        <a href="{{ route('client.payroll.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-xl transition-all duration-200 flex items-center gap-2 shadow-lg shadow-blue-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            {{ __('messages.run_payroll') }}
        </a>
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
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800/50 border-b border-gray-800">
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.select_month') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.status') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.employee_count') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.total_net_payout') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.run_date') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300 text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($runs as $run)
                    <tr class="hover:bg-gray-800/30 transition-colors cursor-pointer" onclick="window.location='{{ route('client.payroll.show', $run->id) }}'">
                        <td class="p-4">
                            <span class="text-white font-medium">{{ $run->month->format('M Y') }}</span>
                        </td>
                        <td class="p-4">
                            @if($run->isConfirmed())
                                <span class="bg-green-500/10 text-green-400 border border-green-500/20 px-2 py-1 rounded-lg text-xs">{{ __('messages.payroll_confirmed') }}</span>
                            @else
                                <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-2 py-1 rounded-lg text-xs">{{ __('messages.payroll_draft') }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-300">
                            {{ $run->payslips_count }}
                        </td>
                        <td class="p-4 text-blue-400 font-bold font-mono">
                            {{ number_format($run->payslips->sum('net_salary'), 2) }}
                        </td>
                        <td class="p-4 text-gray-400 text-sm">
                            {{ $run->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('client.payroll.show', $run->id) }}" class="text-blue-400 hover:text-blue-300 transition-colors text-sm">
                                {{ __('messages.view') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            {{ __('messages.no_payroll_runs') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($runs->hasPages())
        <div class="mt-4">
            {{ $runs->links() }}
        </div>
    @endif
</div>
@endsection
