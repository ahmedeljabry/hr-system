@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 mb-8 border border-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('Super Admin Dashboard') }}</h1>
                <p class="text-gray-600">{{ __('System overview and management tools') }}</p>
            </div>
            <div class="bg-blue-50 px-4 py-2 rounded-xl">
                <span class="text-sm font-medium text-blue-700">{{ __('Super Admin') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ __('Total Clients') }}</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_clients']) }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl">
                    <span class="text-xl">👥</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ __('Total Employees') }}</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_employees']) }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl">
                    <span class="text-xl">👤</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ __('Active Subscriptions') }}</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($stats['active_count']) }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl">
                    <span class="text-xl">✅</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ __('Suspended Subscriptions') }}</p>
                    <p class="text-3xl font-bold text-amber-600">{{ number_format($stats['suspended_count']) }}</p>
                </div>
                <div class="bg-amber-50 p-3 rounded-xl">
                    <span class="text-xl">⏸️</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">{{ __('Expired Subscriptions') }}</p>
                    <p class="text-3xl font-bold text-red-600">{{ number_format($stats['expired_count']) }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-xl">
                    <span class="text-xl">❌</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.clients.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ __('Clients') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Manage client subscriptions and view details') }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl group-hover:bg-blue-100 transition-colors">
                    <span class="text-xl">👥</span>
                </div>
            </div>
            <div class="flex items-center text-blue-600 font-medium text-sm">
                <span>{{ __('View All Clients') }}</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </div>
        </a>

        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 border-dashed">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-400">{{ __('System Reports') }}</h3>
                    <p class="text-sm text-gray-400">{{ __('Advanced analytics and reporting tools') }}</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-xl">
                    <span class="text-xl text-gray-300">📊</span>
                </div>
            </div>
            <div class="flex items-center text-gray-400 font-medium text-sm">
                <span>{{ __('Coming Soon') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection