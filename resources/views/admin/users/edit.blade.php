@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Edit User') }}</h1>
            <p class="text-gray-600 mt-1">{{ $user->name }} ({{ $user->email }})</p>
        </div>
        <a href="{{ route('admin.clients.index') }}" class="text-secondary hover:text-secondary/80 font-bold">
            ← {{ __('Back to Clients') }}
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

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 max-w-2xl">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PATCH')

            <div class="mb-6">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all @error('name') border-red-300 @enderror">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all @error('email') border-red-300 @enderror">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-secondary text-white font-bold py-3 px-6 rounded-xl hover:bg-secondary/90 transition-all focus:ring-2 focus:ring-primary focus:ring-offset-2 shadow-lg">
                    {{ __('Update User') }}
                </button>
                <a href="{{ route('admin.clients.index') }}" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-xl hover:bg-gray-200 transition-colors text-center">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection