@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $client->name }}</h1>
            <p class="text-gray-600 mt-1">{{ __('Client Details & Employees') }}</p>
        </div>
        <a href="{{ route('admin.clients.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            ← {{ __('Back to Clients') }}
        </a>
    </div>

    <!-- Client Info Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('Client Information') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ __('Company Name') }}</p>
                <p class="text-lg font-semibold text-gray-900">{{ $client->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">{{ __('Subscription Status') }}</p>
                @if($client->isActive())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-50 text-green-600 border border-green-100">{{ __('Active') }}</span>
                @elseif($client->isSuspended())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-amber-50 text-amber-600 border border-amber-100">{{ __('Suspended') }}</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-50 text-red-600 border border-red-100">{{ __('Expired') }}</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">{{ __('Subscription End') }}</p>
                <p class="text-lg font-semibold text-gray-900">{{ $client->subscription_end ? $client->subscription_end->format('Y-m-d') : '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">{{ __('Employees') }} ({{ $employees->count() }})</h2>
        </div>

        @if($employees->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('No employees found.') }}</h3>
                <p class="text-gray-500">{{ __('This client has not added any employees yet.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-start">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Name') }}</th>
                            <th class="px-6 py-4 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Position') }}</th>
                            <th class="px-6 py-4 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Hire Date') }}</th>
                            <th class="px-6 py-4 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Login Status') }}</th>
                            <th class="px-6 py-4 text-end text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($employees as $employee)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $employee->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $employee->position }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $employee->hire_date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($employee->user_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                        {{ __('Has Login') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-700 border border-gray-100">
                                        {{ __('No Login') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                @if($employee->user)
                                    <a href="{{ route('admin.users.edit', $employee->user->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ __('Edit User') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection