@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Edit Asset') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('Update asset details and custody information.') }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
            <ul class="list-disc list-inside text-sm text-red-700 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('client.assets.update', $asset) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="type" class="block text-sm font-bold text-gray-700">{{ __('Property Type') }}</label>
                <input type="text" name="type" id="type" value="{{ old('type', $asset->type) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
            </div>

            <div>
                <label for="serial_number" class="block text-sm font-bold text-gray-700">{{ __('Serial Number') }}</label>
                <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
            </div>
        </div>

        <div>
            <label for="employee_id" class="block text-sm font-bold text-gray-700">{{ __('Custodian') }}</label>
            <select name="employee_id" id="employee_id"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
                <option value="">{{ __('Inventory (Company)') }}</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id', $asset->employee_id) == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->position }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="assigned_date" class="block text-sm font-bold text-gray-700">{{ __('Assignment Date') }}</label>
                <input type="date" name="assigned_date" id="assigned_date" value="{{ old('assigned_date', $asset->assigned_date->format('Y-m-d')) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
            </div>

            <div>
                <label for="returned_date" class="block text-sm font-bold text-gray-700">{{ __('Return Date') }}</label>
                <input type="date" name="returned_date" id="returned_date" value="{{ old('returned_date', $asset->returned_date?->format('Y-m-d')) }}"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-bold text-gray-700">{{ __('Asset Details') }}</label>
            <textarea name="description" id="description" rows="3"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">{{ old('description', $asset->description) }}</textarea>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-50">
            <a href="{{ route('client.assets.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-2.5 border border-transparent text-sm font-extrabold rounded-lg shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all hover:-translate-y-0.5 active:translate-y-0">
                {{ __('Update Asset') }}
            </button>
        </div>
    </form>
</div>
@endsection
