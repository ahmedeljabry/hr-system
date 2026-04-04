@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Expiry Warning Banner -->
        @if($showExpiryWarning)
            <div class="mb-8 bg-amber-50 border-l-4 border-amber-400 p-6 rounded-2xl shadow-sm animate-pulse">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-amber-100 p-2 rounded-lg">
                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-amber-900 tracking-tight">{{ app(\App\Services\ReminderPhraseService::class)->getParsedMessage(\App\Enums\NotificationEvent::SUBSCRIPTION_EXPIRING, ['days' => $daysUntilExpiry]) }}</h3>
                        <p class="mt-1 text-xs text-amber-700 opacity-80">{{ __('messages.contact_admin_to_renew') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-8 relative group">
            <div class="relative z-10">
                <h1 class="text-4xl font-extrabold mb-2 tracking-tight">{{ __('messages.dashboard') }}</h1>
                <p class="text-blue-100 text-lg opacity-90">{{ __('messages.welcome') }}, {{ Auth::user()->name }}</p>
            </div>
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-white opacity-10 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-white opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Client Info Card -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transform hover:translate-y-[-4px] transition-all duration-300">
                <div class="flex items-center space-x-4 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }} mb-6">
                    <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-4 18V4"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight">{{ __('messages.company_name') }}</h2>
                </div>
                <p class="text-gray-900 text-xl font-semibold">{{ $client->name }}</p>
                
                <div class="mt-8 pt-6 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-xs uppercase font-bold text-gray-400 tracking-widest">{{ __('messages.status') }}</span>
                    @if($client->isActive())
                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-bold border border-green-100 ring-2 ring-green-100 ring-offset-2">
                            {{ __('messages.subscription_active') }}
                        </span>
                    @else
                        <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-bold border border-red-100 ring-2 ring-red-100 ring-offset-2">
                            {{ __('messages.subscription_expired') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Employee Metric Card -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 transform hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="flex items-center space-x-4 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }} mb-6">
                    <div class="bg-indigo-50 p-3 rounded-xl border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight">{{ __('messages.employees') }}</h2>
                </div>
                
                <div class="flex items-baseline space-x-2 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                    <span class="text-5xl font-black text-gray-900">{{ $employeeCount }}</span>
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ __('messages.total') }}</span>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-50 flex flex-col space-y-4">
                    <a href="{{ route('client.employees.index') }}" class="flex items-center justify-between group-hover:text-indigo-600 transition-colors">
                        <span class="text-sm font-bold uppercase tracking-tight">{{ __('messages.manage_employees') }}</span>
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1 {{ app()->getLocale() == 'ar' ? 'rotate-180 group-hover:-translate-x-1' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('client.payroll.index') }}" class="flex items-center justify-between hover:text-indigo-600 transition-colors">
                        <span class="text-sm font-bold uppercase tracking-tight">{{ __('messages.manage_payroll') }}</span>
                        <svg class="w-5 h-5 transition-transform hover:translate-x-1 {{ app()->getLocale() == 'ar' ? 'rotate-180 hover:-translate-x-1' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Future Feature Placeholder (Reports) -->
            <div class="bg-gray-50 p-8 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center opacity-70">
                <div class="p-4 bg-white rounded-2xl shadow-sm mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">{{ __('messages.reports') }}</h3>
                <p class="text-xs text-gray-400">{{ __('messages.coming_soon') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
