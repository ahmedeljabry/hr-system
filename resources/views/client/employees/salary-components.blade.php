@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
                {{ __('messages.salary_components') }}: {{ $employee->name }}
            </h1>
            <p class="text-gray-400 mt-2 text-sm">{{ $employee->position }}</p>
        </div>
        <a href="{{ route('client.employees.show', $employee->id) }}" class="text-gray-400 hover:text-white transition-colors duration-200">
            {{ __('messages.back') }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add New Component -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl">
        <form method="POST" action="{{ route('client.salary-components.store', $employee->id) }}" class="flex items-end gap-4">
            @csrf
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.type') }}</label>
                <select name="type" required class="w-full bg-gray-800 border outline-none border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
                    <option value="allowance">{{ __('messages.allowance') }}</option>
                    <option value="deduction">{{ __('messages.deduction') }}</option>
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.component_name') }}</label>
                <input type="text" name="name" required class="w-full bg-gray-800 outline-none border border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.amount') }}</label>
                <input type="number" step="0.01" name="amount" required class="w-full bg-gray-800 border outline-none border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/30">
                    {{ __('messages.add_component') }}
                </button>
            </div>
        </form>
        @error('type')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        @error('amount')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Components Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800/50 border-b border-gray-800">
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.component_name') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.type') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300">{{ __('messages.amount') }}</th>
                    <th class="p-4 text-sm font-semibold text-gray-300 text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @foreach($employee->salaryComponents as $component)
                    <tr x-data="{ editing: false }" class="hover:bg-gray-800/30 transition-colors">
                        
                        <!-- Display Mode -->
                        <td x-show="!editing" class="p-4 text-gray-300">{{ $component->name }}</td>
                        <td x-show="!editing" class="p-4">
                            @if($component->type === 'allowance')
                                <span class="bg-green-500/10 text-green-400 border border-green-500/20 px-2 py-1 rounded-lg text-xs">{{ __('messages.allowance') }}</span>
                            @else
                                <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-1 rounded-lg text-xs">{{ __('messages.deduction') }}</span>
                            @endif
                        </td>
                        <td x-show="!editing" class="p-4 text-gray-300 font-mono">{{ number_format($component->amount, 2) }}</td>
                        
                        <!-- Edit Mode Form (Spans across cols) -->
                        <td x-show="editing" colspan="3" class="p-4">
                            <form method="POST" action="{{ route('client.salary-components.update', [$employee->id, $component->id]) }}" class="flex gap-2 w-full">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $component->name }}" class="flex-1 bg-gray-800 outline-none border border-gray-700 text-white rounded-lg p-2 text-sm" required>
                                <select name="type" class="w-1/4 bg-gray-800 outline-none border border-gray-700 text-white rounded-lg p-2 text-sm" required>
                                    <option value="allowance" {{ $component->type === 'allowance' ? 'selected' : '' }}>{{ __('messages.allowance') }}</option>
                                    <option value="deduction" {{ $component->type === 'deduction' ? 'selected' : '' }}>{{ __('messages.deduction') }}</option>
                                </select>
                                <input type="number" step="0.01" name="amount" value="{{ $component->amount }}" class="w-1/4 bg-gray-800 outline-none border border-gray-700 text-white rounded-lg p-2 text-sm" required>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">{{ __('messages.save') }}</button>
                                <button type="button" @click="editing = false" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">{{ __('messages.cancel') }}</button>
                            </form>
                        </td>

                        <!-- Actions Column -->
                        <td x-show="!editing" class="p-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button type="button" @click="editing = true" class="text-blue-400 hover:text-blue-300 transition-colors text-sm">
                                    {{ __('messages.edit') }}
                                </button>
                                
                                <form method="POST" action="{{ route('client.salary-components.destroy', [$employee->id, $component->id]) }}" onsubmit="return confirm('{{ __('messages.confirm_delete_employee') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                
                @if($employee->salaryComponents->isEmpty())
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            No salary components yet.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @php
        $totalAllowances = $employee->salaryComponents->where('type', 'allowance')->sum('amount');
        $totalDeductions = $employee->salaryComponents->where('type', 'deduction')->sum('amount');
        $netSalary = $employee->basic_salary + $totalAllowances - $totalDeductions;
    @endphp

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 text-center">
            <h3 class="text-gray-400 text-sm mb-1">{{ __('messages.basic_salary') }}</h3>
            <p class="text-2xl font-semibold text-white">{{ number_format($employee->basic_salary, 2) }}</p>
        </div>
        <div class="bg-green-500/10 border border-green-500/20 rounded-2xl p-6 text-center">
            <h3 class="text-green-400/80 text-sm mb-1">{{ __('messages.total_allowances') }}</h3>
            <p class="text-2xl font-semibold text-green-400">+{{ number_format($totalAllowances, 2) }}</p>
        </div>
        <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-6 text-center">
            <h3 class="text-red-400/80 text-sm mb-1">{{ __('messages.total_deductions') }}</h3>
            <p class="text-2xl font-semibold text-red-400">-{{ number_format($totalDeductions, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-900/40 to-indigo-900/40 border border-blue-500/30 rounded-2xl p-6 text-center shadow-lg shadow-blue-500/10">
            <h3 class="text-blue-300 text-sm mb-1">{{ __('messages.net_salary') }}</h3>
            <p class="text-3xl font-bold text-white">{{ number_format($netSalary, 2) }}</p>
        </div>
    </div>
</div>
@endsection
