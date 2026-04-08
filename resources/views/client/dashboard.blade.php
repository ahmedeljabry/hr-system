@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        
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

        <!-- Action Required Alert Banner -->
        @if($actionRequiredCount > 0)
            <div x-data="{ 
                     count: {{ $actionRequiredCount }},
                     dismissed: localStorage.getItem('action_required_last_count') == {{ $actionRequiredCount }}
                 }" 
                 x-show="!dismissed" 
                 x-cloak
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="mb-8 bg-rose-50 border-s-4 border-rose-500 p-6 rounded-3xl shadow-[0_10px_30px_rgba(244,63,94,0.1)] flex items-center justify-between group relative">
                
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-rose-500/10 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-rose-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-rose-900 font-black tracking-tight text-lg mb-0.5">{{ __('messages.action_required') }}</h4>
                        <p class="text-rose-700 text-sm font-bold opacity-80">{{ __('messages.action_required_items', ['count' => $actionRequiredCount]) }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('client.action-required.index') }}" 
                       @click="localStorage.setItem('action_required_last_count', count)"
                       class="px-8 py-3 bg-rose-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-rose-200 hover:bg-rose-600 transition-all hover:-translate-y-1 active:scale-95">
                        {{ __('messages.view_details') }}
                    </a>
                    <button @click="dismissed = true; localStorage.setItem('action_required_last_count', count)" 
                            class="p-2 text-rose-300 hover:text-rose-600 transition-colors"
                            title="{{ __('messages.dismiss') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.dashboard')" 
            :subtitle="__('messages.welcome') . ', ' . Auth::user()->name"
        />



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

        <!-- Employee Statistics Section -->
        <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Gender Distribution Card -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                    <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.gender_distribution') }}</h3>
                </div>
                
                <div class="grid grid-cols-2 gap-8">
                    <!-- Male -->
                    <div class="p-8 rounded-3xl bg-blue-50/50 border border-blue-100 flex flex-col items-center text-center group">
                        <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-3xl font-black text-blue-900 mb-1">{{ $genderStats['male'] }}</span>
                        <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">{{ __('messages.male') }}</span>
                    </div>
                    <!-- Female -->
                    <div class="p-8 rounded-3xl bg-pink-50/50 border border-pink-100 flex flex-col items-center text-center group">
                        <div class="w-14 h-14 bg-pink-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-3xl font-black text-pink-900 mb-1">{{ $genderStats['female'] }}</span>
                        <span class="text-[10px] font-bold text-pink-400 uppercase tracking-widest">{{ __('messages.female') }}</span>
                    </div>
                </div>
            </div>

            <!-- Age Distribution Card -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-1.5 h-6 bg-secondary rounded-full"></div>
                    <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.age_distribution') }}</h3>
                </div>
                
                <div class="space-y-4">
                    @php
                        $totalWithAge = array_sum($ageStats);
                        $maxAge = max($ageStats) ?: 1;
                    @endphp
                    @foreach(['under_25' => 'bg-indigo-400', '25_35' => 'bg-emerald-400', '35_45' => 'bg-amber-400', 'over_45' => 'bg-rose-400'] as $key => $color)
                        <div class="flex items-center gap-4">
                            <div class="w-20 text-[10px] font-black text-gray-400 uppercase tracking-tighter">{{ __('messages.' . $key) }}</div>
                            <div class="flex-grow h-3 bg-gray-50 rounded-full overflow-hidden border border-gray-100 shadow-inner">
                                <div class="{{ $color }} h-full rounded-full transition-all duration-1000" style="width: {{ $totalWithAge > 0 ? ($ageStats[$key] / $totalWithAge * 100) : 0 }}%"></div>
                            </div>
                            <div class="w-10 text-right text-sm font-black text-secondary">{{ $ageStats[$key] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-12 bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
        <div class="px-10 py-7 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-2 h-6 bg-primary rounded-full"></div>
                <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.announcements') }}</h3>
            </div>
            <a href="{{ route('client.announcements.index') }}" class="text-xs font-black uppercase tracking-widest text-primary hover:text-primary/70 transition-colors">{{ __('messages.view_all') }}</a>
        </div>
        
        <div class="divide-y divide-gray-50">
            @forelse($recentAnnouncements as $announcement)
                <div class="p-10 hover:bg-gray-50 transition-all duration-300 group/announcement">
                    <div class="flex items-center justify-between gap-6">
                        <div class="flex-grow">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-primary/10 text-[9px] font-black text-primary uppercase tracking-widest rounded-full">{{ __('messages.notice') }}</span>
                                <span class="text-[10px] font-bold text-gray-400">{{ $announcement->published_at->translatedFormat('d M, Y') }}</span>
                            </div>
                            <h4 class="text-lg font-black text-secondary mb-2 group-hover/announcement:text-primary transition-colors leading-tight">{{ $announcement->title }}</h4>
                        </div>
                        <a href="{{ route('client.announcements.edit', $announcement) }}" class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-secondary transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4v4h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_announcements_yet') }}</h3>
                </div>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection
