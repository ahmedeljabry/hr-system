@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-8 relative">
            <div class="relative z-10">
                <h1 class="text-4xl font-extrabold mb-2">{{ __('messages.dashboard') }}</h1>
                <p class="text-blue-100 text-lg opacity-90">مرحبًا بك، {{ Auth::user()->name }}</p>
            </div>
            <!-- Decorative circle overlay -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-white opacity-5 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Client Info Card -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transform hover:scale-[1.02] transition-transform">
                <div class="flex items-center space-x-4 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }} mb-4">
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-4 18V4"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ __('messages.company_name') }}</h2>
                </div>
                <p class="text-gray-600 text-lg">{{ Auth::user()->client?->name ?? '---' }}</p>
                
                <div class="mt-6 pt-6 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-xs uppercase font-bold text-gray-400 tracking-wider">الحالة / Status</span>
                    @if(Auth::user()->client?->isActive())
                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-bold border border-green-100">
                            {{ __('messages.subscription_active') }}
                        </span>
                    @else
                    <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-bold border border-red-100">
                        {{ __('messages.subscription_expired') }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- More placeholders to make it look premium -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 opacity-50 border-dashed flex flex-col items-center justify-center text-gray-400">
                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <p class="text-sm font-medium">إدارة الموظفين قيد التطوير</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 opacity-50 border-dashed flex flex-col items-center justify-center text-gray-400">
                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-sm font-medium">التقارير قيد التطوير</p>
            </div>
        </div>
    </div>
</div>
@endsection
