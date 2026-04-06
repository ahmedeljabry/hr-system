@extends('layouts.employee')

@section('content')
<div class="space-y-10">
    <!-- Premium Global Header -->
    <div class="bg-secondary rounded-[2.5rem] shadow-xl p-10 text-white relative overflow-hidden group">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black mb-2 tracking-tight">{{ __('Welcome back') }}, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-primary/70 text-lg opacity-90 font-medium">{{ __('Here is a summary of your portal') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-6 py-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 text-sm font-bold">
                    <span class="opacity-60 uppercase tracking-widest text-[10px] block mb-0.5">{{ __('Today') }}</span>
                    {{ now()->translatedFormat('d M Y') }}
                </div>
            </div>
        </div>
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 text-white opacity-10 group-hover:scale-110 transition-transform duration-1000">
            <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending Tasks -->
        <a href="{{ route('employee.tasks.index') }}" class="bg-white rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] p-7 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-orange-50/50 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ __('Pending Tasks') }}</span>
                    <div class="text-3xl font-black text-secondary">{{ $widgets['pending_tasks'] }}</div>
                </div>
            </div>
            <div class="text-[10px] font-bold text-gray-400 flex items-center gap-2 group-hover:text-orange-500 transition-colors">
                {{ __('View All') }}
                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </a>

        <!-- Assigned Assets -->
        <a href="{{ route('employee.assets.index') }}" class="bg-white rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] p-7 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ __('Assigned Assets') }}</span>
                    <div class="text-3xl font-black text-secondary">{{ $widgets['assigned_assets'] }}</div>
                </div>
            </div>
            <div class="text-[10px] font-bold text-gray-400 flex items-center gap-2 group-hover:text-primary transition-colors">
                {{ __('View All') }}
                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </a>

        <!-- Latest Payslip -->
        <a href="{{ route('employee.payslips.index') }}" class="bg-white rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] p-7 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50/50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ __('messages.latest_payslip') }}</span>
                    <div class="text-2xl font-black text-secondary">
                        @if($widgets['latest_payslip'])
                            {{ number_format($widgets['latest_payslip']->net_salary, 2) }}
                        @else
                            <span class="text-xs font-bold text-gray-300 italic">{{ __('No data available') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-[10px] font-bold text-gray-400 flex items-center gap-2 group-hover:text-emerald-500 transition-colors">
                {{ __('messages.view_payslips') }}
                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </a>

        <!-- Leave Balance -->
        <a href="{{ route('employee.leaves.index') }}" class="bg-white rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] p-7 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ __('Leave Balance') }}</span>
                    <div class="text-3xl font-black text-secondary">
                        @if($widgets['leave_balance'] !== null)
                            {{ $widgets['leave_balance'] }} <span class="text-[10px] font-bold text-gray-300 lowercase">{{ __('days') }}</span>
                        @else
                            <span class="text-xs font-bold text-gray-300 italic">{{ __('No data available') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-[10px] font-bold text-gray-400 flex items-center gap-2 group-hover:text-primary transition-colors">
                {{ __('messages.request_leave') }}
                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Recent Announcements -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500 flex flex-col">
            <div class="px-10 py-7 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary rounded-full"></div>
                    <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.announcements') }}</h3>
                </div>
                <a href="/employee/announcements" class="text-xs font-black uppercase tracking-widest text-primary hover:text-primary/70 transition-colors">{{ __('messages.view_all') }}</a>
            </div>
            
            <div class="divide-y divide-gray-50 flex-grow">
                @forelse($widgets['recent_announcements'] as $announcement)
                    <div class="p-10 hover:bg-primary/5 transition-all duration-300 group/announcement">
                        <div class="flex items-start justify-between gap-6">
                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 bg-primary/10 text-[9px] font-black text-primary uppercase tracking-widest rounded-full">{{ __('messages.notice') }}</span>
                                    <span class="text-[10px] font-bold text-gray-400">{{ $announcement->published_at->translatedFormat('d M, Y') }}</span>
                                </div>
                                <h4 class="text-lg font-black text-secondary mb-3 group-hover/announcement:text-primary transition-colors">{{ $announcement->title }}</h4>
                                <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">{!! nl2br(e(Str::limit($announcement->body, 200))) !!}</p>
                                <div class="mt-5">
                                    <a href="/employee/announcements" class="text-[10px] font-black uppercase tracking-widest text-gray-400 group-hover/announcement:text-primary flex items-center gap-2 transition-all">
                                        {{ __('messages.read_more') }}
                                        <svg class="w-3 h-3 group-hover/announcement:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="hidden sm:block w-24 h-24 rounded-3xl bg-gray-50 overflow-hidden relative group-hover/announcement:scale-105 transition-transform duration-500 shrink-0 border border-gray-100">
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5"></div>
                                <svg class="absolute inset-0 m-auto w-10 h-10 text-primary/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4v4h4"></path></svg>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 01-2-2v-5a2 2 0 012-2h5m5 4h5a2 2 0 012 2v5a2 2 0 01-2 2h-5m-5 4h5a2 2 0 012 2v5a2 2 0 01-2 2h-5m-5-4h5a2 2 0 012 2v5a2 2 0 01-2 2h-5z"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('No announcements yet.') }}</h3>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Secondary Column (e.g., Quick Actions / Tips) -->
        <div class="space-y-6">
            <div class="bg-secondary rounded-[2.5rem] p-8 text-white shadow-xl shadow-secondary/20 relative overflow-hidden group">
                <h3 class="text-xl font-black mb-4 relative z-10">{{ __('messages.quick_actions') }}</h3>
                <div class="space-y-3 relative z-10">
                    <a href="{{ route('employee.leaves.create') }}" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 border border-white/5 rounded-2xl transition-all duration-300 group/btn">
                        <span class="text-sm font-bold">{{ __('Request Leave') }}</span>
                        <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </a>
                    <a href="/employee/tasks" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 border border-white/5 rounded-2xl transition-all duration-300 group/btn">
                        <span class="text-sm font-bold">{{ __('My Tasks') }}</span>
                        <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" id="dash-logout-form">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-4 bg-red-500/20 hover:bg-red-500/30 border border-white/10 rounded-2xl transition-all duration-300 group/logout">
                            <span class="text-sm font-bold text-white">{{ __('Logout') }}</span>
                            <svg class="w-5 h-5 text-white group-hover/logout:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6-4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="absolute bottom-[-2rem] right-[-2rem] w-32 h-32 bg-white opacity-5 rounded-full group-hover:scale-110 transition-transform"></div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-[0_20px_50px_rgba(0,0,0,0.03)]">
                <h3 class="text-lg font-black text-secondary tracking-tight mb-4">{{ __('messages.need_help') }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">{{ __('messages.support_text') }}</p>
                <a href="mailto:support@company.com" class="inline-flex items-center gap-2 text-secondary font-black text-xs uppercase tracking-widest hover:text-secondary/80 transition-colors">
                    {{ __('messages.contact_hr') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

