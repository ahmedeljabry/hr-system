@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Clients') }}</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-secondary hover:text-secondary/80 font-medium">
            ← {{ __('Back to Dashboard') }}
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl shadow-sm flex items-center">
            <svg class="w-6 h-6 me-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <x-data-table endpoint="{{ route('admin.clients.index') }}">
        <x-slot name="head">
            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Company Name') }}</th>
            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Subscription Status') }}</th>
            <th class="px-6 py-5 text-start text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Employees') }}</th>
            <th class="px-6 py-5 text-end text-xs font-extrabold text-gray-500 uppercase tracking-widest">{{ __('Actions') }}</th>
        </x-slot>
        <x-slot name="body">
            <td class="px-6 py-6 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="bg-primary/20 text-secondary w-10 h-10 rounded-xl flex items-center justify-center font-bold me-3 text-lg lowercase" x-text="item.name.substring(0, 1)"></div>
                    <div>
                        <div class="text-sm font-bold text-gray-900" x-text="item.name"></div>
                        <div class="text-xs text-gray-400">ID: #<span x-text="item.id"></span></div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-6 whitespace-nowrap">
                <!-- Fallback since standard table approach required select forms here -->
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
                      :class="{
                          'bg-green-100 text-green-700': item.status === 'active',
                          'bg-amber-100 text-amber-700': item.status === 'suspended',
                          'bg-red-100 text-red-700': item.status === 'expired'
                      }" x-text="item.status"></span>
            </td>
            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-900 font-bold" x-text="item.employees_count"></td>
            <td class="px-6 py-6 whitespace-nowrap text-end text-sm font-medium">
                <a :href="`/admin/clients/${item.id}`" class="text-secondary hover:text-secondary/80 font-medium">
                    {{ __('View Details') }}
                </a>
            </td>
        </x-slot>
    </x-data-table>
</div>
@endsection