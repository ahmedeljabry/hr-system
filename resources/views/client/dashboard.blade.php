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
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-8 relative group border border-primary/20">
            <div class="relative z-10">
                <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.dashboard') }}</h1>
                <p class="text-gray-300 text-lg opacity-90">{{ __('messages.welcome') }}, {{ Auth::user()->name }}</p>
            </div>
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Client Info Card -->
            <div class="group relative bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] hover:translate-y-[-8px] transition-all duration-500 overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-125"></div>
                <div class="absolute bottom-0 left-0 w-20 h-20 bg-secondary/5 rounded-tr-[3rem] -ml-5 -mb-5"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-10">
                            <div class="bg-primary/10 p-4 rounded-2xl border border-primary/20 shadow-inner group-hover:bg-primary/20 transition-colors duration-500">
                                <svg class="w-8 h-8 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-4 18V4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">{{ __('messages.company_name') }}</h3>
                                <p class="text-3xl font-black text-secondary leading-none">{{ $client->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-6">
                        <div class="p-6 bg-gray-50/50 rounded-3xl border border-gray-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('messages.status') }}</span>
                            @if($client->isActive())
                                <div class="flex items-center space-x-2 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(34,197,94,0.4)]"></span>
                                    <span class="text-sm font-bold text-green-600 bg-green-50 px-4 py-1.5 rounded-full border border-green-100">
                                        {{ __('messages.subscription_active') }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center space-x-2 {{ app()->getLocale() == 'ar' ? 'space-x-reverse' : '' }}">
                                    <span class="w-3 h-3 bg-red-400 rounded-full"></span>
                                    <span class="text-sm font-bold text-red-600 bg-red-50 px-4 py-1.5 rounded-full border border-red-100">
                                        {{ __('messages.subscription_expired') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Metric Card -->
            <div class="group relative bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] hover:translate-y-[-8px] transition-all duration-500 overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute top-0 left-0 w-32 h-32 bg-secondary/5 rounded-br-[5rem] -ml-10 -mt-10 transition-transform duration-700 group-hover:scale-125"></div>
                
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-10">
                            <div class="bg-primary/10 p-4 rounded-2xl border border-primary/20 shadow-inner group-hover:bg-primary transition-all duration-700">
                                <svg class="w-8 h-8 text-secondary group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.employees') }}</h3>
                        </div>
                    </div>
                    
                    <div class="flex-grow flex items-center justify-center mb-10 py-4">
                        <div class="text-center relative">
                            <span class="text-8xl font-black text-secondary tracking-tighter drop-shadow-sm">{{ $employeeCount }}</span>
                            <div class="mt-2 text-xs font-bold text-gray-400 uppercase tracking-[0.3em]">{{ __('messages.total_registered') }}</div>
                            <!-- Subtle aura behind number on hover -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-primary/20 blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-1000 -z-10"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('client.employees.index') }}" 
                           class="flex flex-col items-center justify-center p-6 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-secondary hover:border-secondary transition-all duration-500 group/btn">
                            <svg class="w-6 h-6 mb-2 text-primary group-hover/btn:translate-y-[-2px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-[10px] font-bold text-secondary group-hover/btn:text-white uppercase tracking-wider text-center leading-tight">
                                {{ __('messages.manage_employees') }}
                            </span>
                        </a>
                        <a href="{{ route('client.payroll.index') }}" 
                           class="flex flex-col items-center justify-center p-6 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-secondary hover:border-secondary transition-all duration-500 group/btn">
                            <svg class="w-6 h-6 mb-2 text-primary group-hover/btn:translate-y-[-2px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.406 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.406-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-[10px] font-bold text-secondary group-hover/btn:text-white uppercase tracking-wider text-center leading-tight">
                                {{ __('messages.manage_payroll') }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
