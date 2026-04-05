@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Leave Management') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('Review and manage employee leave requests.') }}</p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('client.leaves.types') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-bold rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ __('Leave Types') }}
        </a>
    </div>
</div>

@if($pendingCount > 0)
<div class="mb-4 bg-amber-50 border-l-4 border-amber-400 p-4 rounded-md shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3 {{ app()->getLocale() == 'ar' ? 'mr-3 ml-0' : '' }}">
            <p class="text-sm text-amber-700 font-bold">{{ __(':count pending leave request(s) require your attention.', ['count' => $pendingCount]) }}</p>
        </div>
    </div>
</div>
@endif

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

<!-- Status Filter Tabs -->
<div class="mb-6 flex items-center gap-2 flex-wrap">
    <a href="{{ route('client.leaves.index') }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ !$status ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        {{ __('All') }}
    </a>
    <a href="{{ route('client.leaves.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        {{ __('Pending') }}
    </a>
    <a href="{{ route('client.leaves.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $status === 'approved' ? 'bg-green-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        {{ __('Approved') }}
    </a>
    <a href="{{ route('client.leaves.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $status === 'rejected' ? 'bg-red-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        {{ __('Rejected') }}
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Employee') }}</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Leave Type') }}</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Dates') }}</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Days') }}</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($requests as $req)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $req->employee->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $req->leaveType->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $req->start_date->format('d/m/Y') }} — {{ $req->end_date->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $req->days_count }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ][$req->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $statusClasses }}">
                                {{ __($req->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                            @if($req->isPending())
                                <div class="flex items-center justify-end gap-2 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}" x-data="{ showModal: false }">
                                    <form action="{{ route('client.leaves.approve', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-md bg-green-50 text-green-700 hover:bg-green-100 transition-colors">
                                            {{ __('Approve') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('client.leaves.reject', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-md bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                            {{ __('Reject') }}
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">{{ $req->reviewed_at?->diffForHumans() }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-bold">
                            {{ __('No leave requests found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($requests->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $requests->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
