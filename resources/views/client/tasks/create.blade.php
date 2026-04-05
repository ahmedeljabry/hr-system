@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('Create Task') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('Enter task details and assign to an employee.') }}</p>
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

    <form action="{{ route('client.tasks.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
        @csrf
        
        <div>
            <label for="title" class="block text-sm font-bold text-gray-700">{{ __('Task Title') }}</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
        </div>

        <div>
            <label for="employee_id" class="block text-sm font-bold text-gray-700">{{ __('Assign To') }}</label>
            <select name="employee_id" id="employee_id"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
                <option value="">{{ __('Unassigned') }}</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->position }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="due_date" class="block text-sm font-bold text-gray-700">{{ __('Due Date') }}</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
            </div>

            <div>
                <label for="status" class="block text-sm font-bold text-gray-700">{{ __('Initial Status') }}</label>
                <select name="status" id="status" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">
                    <option value="todo" {{ old('status') == 'todo' ? 'selected' : '' }}>{{ __('To Do') }}</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                    <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>{{ __('Done') }}</option>
                </select>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-bold text-gray-700">{{ __('Description') }}</label>
            <textarea name="description" id="description" rows="4"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5">{{ old('description') }}</textarea>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-50">
            <a href="{{ route('client.tasks.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-2.5 border border-transparent text-sm font-extrabold rounded-lg shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/50 transition-all hover:-translate-y-0.5 active:translate-y-0">
                {{ __('Create Task') }}
            </button>
        </div>
    </form>
</div>
@endsection
