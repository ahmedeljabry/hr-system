@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('messages.employees') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('messages.total_employees') }}: {{ $employees->total() }}</p>
    </div>
    
    <div class="flex items-center gap-3">
        <button @click="$dispatch('open-slide-over-add-employee')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('messages.add_employee') }}
        </button>
        
        <!-- Import link (Phase 4 placeholder/route) -->
        @if(Route::has('client.employees.import.form'))
        <a href="{{ route('client.employees.import.form') }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            {{ __('messages.import_employees') }}
        </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
            </div>
        </div>
    </div>
@endif

<div x-data="{ viewMode: localStorage.getItem('view_mode') || 'grid' }" @view-changed.window="viewMode = $event.detail">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <!-- Search and Filters -->
        <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('client.employees.index') }}" class="w-full sm:max-w-md">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 inline-start-0 ps-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full ps-10 pe-4 sm:text-sm border flex-1 border border-gray-300 rounded-md py-2.5 focus:ring-primary focus:border-primary" placeholder="{{ __('messages.search_employees') }}">
                </div>
            </form>
            <x-view-toggle />
        </div>

        <!-- Grid View -->
        <div x-show="viewMode === 'grid'" class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($employees as $employee)
                    @include('client.employees._grid-card', ['employee' => $employee])
                @empty
                    <div class="col-span-full">
                        <x-empty-state icon="M17 20h5..." message="{{ __('messages.no_employees') }}" />
                    </div>
                @endforelse
            </div>
        </div>

        <!-- List View -->
        <div x-show="viewMode === 'list'" class="overflow-x-auto" style="display: none;">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.employee_name') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.position') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.national_id_number') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.basic_salary') }}</th>
                        <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.hire_date') }}</th>
                        <th class="px-6 py-3 text-end text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($employees as $employee)
                        @include('client.employees._list-row', ['employee' => $employee])
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">{{ __('messages.no_employees') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($employees->hasPages())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
            {{ $employees->links() }}
        </div>
    @endif
</div>

<!-- Add Employee Slide-over -->
<x-slide-over id="add-employee" title="{{ __('messages.add_employee') }}">
    <iframe src="{{ route('client.employees.create') }}?embedded=true" class="w-full h-full border-0" title="{{ __('messages.add_employee') }}"></iframe>
</x-slide-over>
@endsection
