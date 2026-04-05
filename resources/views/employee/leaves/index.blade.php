@extends('layouts.employee')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('My Leaves') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('View your leave balance and request history.') }}</p>
    </div>
    
    <a href="{{ route('employee.leaves.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all hover:-translate-y-0.5">
        <svg class="-ml-1 mr-2 h-5 w-5 {{ app()->getLocale() == 'ar' ? 'ml-2 -mr-1' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
        </svg>
        {{ __('Request Leave') }}
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 {{ app()->getLocale() == 'ar' ? 'mr-3 ml-0' : '' }}">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 {{ app()->getLocale() == 'ar' ? 'mr-3 ml-0' : '' }}">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Leave Balance Cards -->
@if(count($balanceSummary) > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach($balanceSummary as $balance)
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-shadow">
            <h3 class="text-sm font-medium text-gray-500 mb-2">{{ $balance['type']->name }}</h3>
            <div class="flex items-end justify-between">
                <div>
                    <span class="text-2xl font-bold text-gray-900">{{ $balance['remaining'] }}</span>
                    <span class="text-sm text-gray-400">/ {{ $balance['max_days'] > 0 ? $balance['max_days'] : '∞' }}</span>
                </div>
                @if($balance['max_days'] > 0)
                    <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                        @php $pct = $balance['max_days'] > 0 ? min(100, ($balance['used_days'] / $balance['max_days']) * 100) : 0; @endphp
                        <div class="h-full rounded-full {{ $pct > 80 ? 'bg-red-500' : ($pct > 50 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ $pct }}%"></div>
                    </div>
                @endif
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ __(':used used', ['used' => $balance['used_days']]) }}</p>
        </div>
    @endforeach
</div>
@else
<div class="mb-8 bg-gray-50 rounded-2xl p-8 text-center text-gray-500">
    <p class="font-bold">{{ __('No leave types configured by your organization yet.') }}</p>
</div>
@endif

<!-- Leave Request History -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
        <h3 class="font-bold text-gray-900">{{ __('Request History') }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Leave Type') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Dates') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Days') }}</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">{{ __('Status') }}</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Comment') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($requests as $req)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $req->leaveType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $req->start_date->format('d/m/Y') }} — {{ $req->end_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $req->days_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $sc = [
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ][$req->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $sc }}">{{ __($req->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $req->reviewer_comment ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-bold">{{ __('No leave requests found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
