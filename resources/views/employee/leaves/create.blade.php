@extends('layouts.employee')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Request Leave') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('Submit a new leave request for approval.') }}</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Balance Summary -->
    @if(count($balanceSummary) > 0)
    <div class="mb-6 grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach($balanceSummary as $balance)
            <div class="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                <p class="text-xs text-gray-500 font-medium truncate">{{ $balance['type']->name }}</p>
                <p class="text-lg font-bold text-gray-900 mt-1">{{ $balance['remaining'] }} <span class="text-xs text-gray-400 font-normal">{{ __('remaining') }}</span></p>
            </div>
        @endforeach
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('employee.leaves.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="leave_type_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Leave Type') }} <span class="text-red-500">*</span></label>
                <select name="leave_type_id" id="leave_type_id" required
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-2.5 border">
                    <option value="">{{ __('Select leave type...') }}</option>
                    @foreach($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }} ({{ $type->max_days_per_year > 0 ? $type->max_days_per_year . ' ' . __('days/year') : __('Unlimited') }})
                        </option>
                    @endforeach
                </select>
                @error('leave_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-2.5 border" value="{{ old('start_date') }}">
                    @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('End Date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-2.5 border" value="{{ old('end_date') }}">
                    @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Reason') }}</label>
                <textarea name="reason" id="reason" rows="3"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-2.5 border" placeholder="{{ __('Optional reason for your leave request...') }}">{{ old('reason') }}</textarea>
                @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('employee.leaves.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">{{ __('Cancel') }}</a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all hover:-translate-y-0.5">
                    {{ __('Submit Request') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
