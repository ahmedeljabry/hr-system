@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-gray-900 border border-gray-800 p-6 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
            {{ __('messages.run_payroll') }}
        </h1>
        <a href="{{ route('client.payroll.index') }}" class="text-gray-400 hover:text-white transition-colors duration-200">
            {{ __('messages.back') }}
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl">
        <form method="POST" action="{{ route('client.payroll.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.select_month') }}</label>
                <input type="month" name="month" required class="w-full bg-gray-800 border outline-none border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3">
                @error('month')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/30">
                    {{ __('messages.run_payroll') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
