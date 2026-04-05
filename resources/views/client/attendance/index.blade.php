@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Attendance') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('Record daily attendance for your employees.') }}</p>
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

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-50 bg-gray-50/50">
        <form method="GET" action="{{ route('client.attendance.index') }}" class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="w-full md:w-64">
                <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Select Date') }}</label>
                <input type="date" id="date" name="date" value="{{ $date }}" 
                    max="{{ now()->format('Y-m-d') }}"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 shadow-sm"
                    onchange="this.form.submit()">
            </div>
            <div class="pb-2">
                <span class="text-sm text-gray-500 font-medium whitespace-nowrap bg-blue-50 px-3 py-1 rounded-full text-blue-700">
                    <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </span>
            </div>
        </form>
    </div>
</div>

<form action="{{ route('client.attendance.store') }}" method="POST">
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Employee') }}
                        </th>
                        <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <span class="text-green-600">{{ __('Present') }}</span>
                        </th>
                        <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <span class="text-yellow-600">{{ __('Late') }}</span>
                        </th>
                        <th scope="col" class="px-4 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <span class="text-red-600">{{ __('Absent') }}</span>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Notes') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($employees as $employee)
                        @php 
                            $record = $records->get($employee->id);
                            $currentStatus = old("attendance.{$employee->id}.status", $record?->status ?: 'present');
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $employee->name }}</div>
                                <div class="text-xs text-gray-500 font-medium">{{ $employee->position }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center">
                                    <input type="radio" name="attendance[{{ $employee->id }}][status]" value="present" 
                                        {{ $currentStatus == 'present' ? 'checked' : '' }}
                                        class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 cursor-pointer shadow-sm">
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center">
                                    <input type="radio" name="attendance[{{ $employee->id }}][status]" value="late" 
                                        {{ $currentStatus == 'late' ? 'checked' : '' }}
                                        class="h-5 w-5 text-yellow-500 focus:ring-yellow-400 border-gray-300 cursor-pointer shadow-sm">
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center">
                                    <input type="radio" name="attendance[{{ $employee->id }}][status]" value="absent" 
                                        {{ $currentStatus == 'absent' ? 'checked' : '' }}
                                        class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 cursor-pointer shadow-sm">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" name="attendance[{{ $employee->id }}][notes]" 
                                    value="{{ old("attendance.{$employee->id}.notes", $record?->notes) }}"
                                    placeholder="{{ __('Add optional note...') }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md py-1.5 px-3 shadow-sm bg-gray-50/50 focus:bg-white transition-all">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-16 w-16 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="text-gray-500 font-bold text-lg">{{ __('No employees found to record attendance.') }}</p>
                                    <a href="{{ route('client.employees.create') }}" class="mt-4 text-blue-600 hover:text-blue-800 font-semibold">{{ __('Add your first employee') }} &rarr;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->isNotEmpty())
            <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end">
                <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-extrabold rounded-lg shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Save Attendance Records') }}
                </button>
            </div>
        @endif
    </div>
</form>
@endsection
