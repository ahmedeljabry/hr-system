@extends('layouts.employee')

@section('content')
<div class="mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">{{ __('Welcome back') }}, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-blue-100">{{ __('Here is a summary of your portal') }}</p>
        </div>
        <div class="absolute top-0 right-0 -mt-16 -mr-16 text-white opacity-10">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Pending Tasks -->
    <a href="{{ route('employee.tasks.index') }}" class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow group flex flex-col justify-between h-full relative overflow-hidden">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">{{ __('Pending Tasks') }}</h3>
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $widgets['pending_tasks'] }}</div>
        </div>
    </a>

    <!-- Assigned Assets -->
    <a href="{{ route('employee.assets.index') }}" class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow group flex flex-col justify-between h-full relative overflow-hidden">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">{{ __('Assigned Assets') }}</h3>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $widgets['assigned_assets'] }}</div>
        </div>
    </a>

    <!-- Latest Payslip -->
    <a href="{{ route('employee.payslips.index') }}" class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow group flex flex-col justify-between h-full relative overflow-hidden">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">{{ __('Latest Payslip') }}</h3>
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900">
                @if($widgets['latest_payslip'])
                    {{ number_format($widgets['latest_payslip']->net_salary, 2) }}
                @else
                    <span class="text-sm font-normal text-gray-400">{{ __('No data available') }}</span>
                @endif
            </div>
        </div>
    </a>

    <!-- Leave Balance -->
    <a href="{{ route('employee.leaves.index') }}" class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow group flex flex-col justify-between h-full relative overflow-hidden">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium">{{ __('Leave Balance') }}</h3>
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">
                @if($widgets['leave_balance'] !== null)
                    {{ $widgets['leave_balance'] }} <span class="text-sm font-normal text-gray-400">{{ __('days') }}</span>
                @else
                    <span class="text-sm font-normal text-gray-400">{{ __('No data available') }}</span>
                @endif
            </div>
        </div>
    </a>
</div>

<!-- Recent Announcements -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">{{ __('Announcements') }}</h3>
        <a href="/employee/announcements" class="text-sm text-blue-600 hover:text-blue-700 font-medium">{{ __('View All') }}</a>
    </div>
    
    <div class="divide-y divide-gray-50">
        @forelse($widgets['recent_announcements'] as $announcement)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-bold text-gray-900 mb-1">{{ $announcement->title }}</h4>
                        <p class="text-gray-600 text-sm line-clamp-2">{!! nl2br(e(Str::limit($announcement->body, 150))) !!}</p>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap {{ app()->getLocale() == 'ar' ? 'mr-4' : 'ml-4' }}">
                        {{ $announcement->published_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                {{ __('No announcements yet.') }}
            </div>
        @endforelse
    </div>
</div>
@endsection
