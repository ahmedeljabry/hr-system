@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-primary">
            Create Login for {{ $employee->name }}
        </h1>
        <a href="{{ route('client.employees.show', $employee->id) }}" class="text-gray-400 hover:text-white transition-colors duration-200">
            {{ __('messages.back') }}
        </a>
    </div>

    @if($employee->user_id)
        <div class="bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 p-4 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p>{{ __('messages.employee_already_has_account') }}</p>
        </div>
    @else
        <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-xl p-6">
            <form method="POST" action="{{ route('client.employees.store-account', $employee->id) }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" required class="w-full bg-gray-800 border outline-none border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="pt-4 text-right">
                    <button type="submit" class="bg-secondary hover:bg-secondary/90 text-white font-medium py-3 px-8 rounded-xl transition-all duration-200 shadow-lg shadow-secondary/30">
                        {{ __('messages.create_account') }}
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
