@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Leave Types') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('Configure leave types and annual allowances for your employees.') }}</p>
    </div>
    <a href="{{ route('client.leaves.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-bold rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition-all">
        ← {{ __('Back to Requests') }}
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add Leave Type Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Add Leave Type') }}</h3>
            <form action="{{ route('client.leaves.store-type') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                    <input type="text" name="name" id="name" required placeholder="{{ __('e.g. Annual Leave') }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-2.5 border" value="{{ old('name') }}">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="max_days_per_year" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Max Days Per Year') }}</label>
                    <input type="number" name="max_days_per_year" id="max_days_per_year" required min="0" max="365"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-2.5 border" value="{{ old('max_days_per_year', 0) }}">
                    <p class="text-xs text-gray-400 mt-1">{{ __('Set to 0 for unlimited.') }}</p>
                    @error('max_days_per_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-bold rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all">
                    {{ __('Save') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Existing Leave Types -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Name') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('Max Days/Year') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($leaveTypes as $type)
                        <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ editing: false }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span x-show="!editing" class="text-sm font-bold text-gray-900">{{ $type->name }}</span>
                                <template x-if="editing">
                                    <form action="{{ route('client.leaves.update-type', $type) }}" method="POST" class="flex items-center gap-2" id="edit-form-{{ $type->id }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $type->name }}" class="text-sm rounded-md border-gray-300 px-2 py-1" required>
                                        <input type="number" name="max_days_per_year" value="{{ $type->max_days_per_year }}" class="text-sm rounded-md border-gray-300 px-2 py-1 w-20" required min="0">
                                        <button type="submit" class="px-2 py-1 text-xs font-bold rounded bg-blue-600 text-white hover:bg-blue-700">{{ __('Save') }}</button>
                                        <button type="button" @click="editing = false" class="px-2 py-1 text-xs font-bold rounded bg-gray-200 text-gray-700 hover:bg-gray-300">{{ __('Cancel') }}</button>
                                    </form>
                                </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span x-show="!editing" class="text-sm text-gray-600">{{ $type->max_days_per_year > 0 ? $type->max_days_per_year : __('Unlimited') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                <div x-show="!editing" class="flex items-center justify-end gap-2 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}">
                                    <button @click="editing = true" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-1.5 rounded-md transition-colors" title="{{ __('Edit') }}">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form action="{{ route('client.leaves.destroy-type', $type) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md transition-colors" title="{{ __('Delete') }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500 font-bold">
                                {{ __('No leave types configured yet. Add one to get started.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
