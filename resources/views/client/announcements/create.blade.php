@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center space-x-4 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Create Announcement') }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ __('Publish a new announcement to your employees') }}</p>
        </div>
    </div>
    <a href="{{ route('client.announcements.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
        {{ __('Back') }}
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
    <form action="{{ route('client.announcements.store') }}" method="POST">
        @csrf
        <div class="p-6 md:p-8 space-y-6">
            
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Title') }}</label>
                <input type="text" name="title" id="title" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3 border bg-gray-50/50 @error('title') border-red-300 @enderror" value="{{ old('title') }}" required autofocus>
                @error('title')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Body -->
            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Announcement Body') }}</label>
                <textarea name="body" id="body" rows="6" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-3 border bg-gray-50/50 @error('body') border-red-300 @enderror" required>{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
            <a href="{{ route('client.announcements.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-colors">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-xl shadow-sm hover:bg-blue-700 transition-colors">
                {{ __('Create Announcement') }}
            </button>
        </div>
    </form>
</div>
@endsection
