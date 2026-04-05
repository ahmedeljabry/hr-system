@extends('layouts.employee')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center text-gray-500 hover:shadow-md transition-shadow relative overflow-hidden group">
        
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-purple-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>

        <div class="relative z-10 flex flex-col items-center justify-center">
            
            <div class="w-20 h-20 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-500 mb-6 shadow-inner transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('My Leaves') }}</h2>
            <p class="text-gray-600 mb-6 max-w-sm mx-auto">{{ __('No leave records found.') }}</p>
            
            <div class="inline-flex items-center px-4 py-2 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50 text-sm font-medium text-gray-500">
                <span class="mr-2 {{ app()->getLocale() == 'ar' ? 'ml-2 mr-0' : '' }}">🚧</span>
                {{ __('This feature will be available soon.') }}
            </div>
            
        </div>
    </div>
</div>
@endsection
