@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <div class="space-y-10">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.welcome_back_with_name', ['name' => Auth::user()->employee ? Auth::user()->employee->name : Auth::user()->name])" 
            :subtitle="__('messages.portal_summary')"
        />

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Employee Profile Info Card -->
        <div class="group relative bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] hover:translate-y-[-8px] transition-all duration-500 overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] -mr-10 -mt-10 transition-transform duration-700 group-hover:scale-125"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-secondary/5 rounded-tr-[3rem] -ml-5 -mb-5"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-8">
                        <div class="bg-primary/10 p-4 rounded-2xl border border-primary/20 shadow-inner group-hover:bg-primary/20 transition-colors duration-500">
                             <x-avatar :name="Auth::user()->employee ? Auth::user()->employee->name : Auth::user()->name" size="lg" class="rounded-xl" />
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">{{ __('messages.my_profile') }}</h3>
                            <p class="text-3xl font-black text-secondary leading-none">{{ Auth::user()->employee ? Auth::user()->employee->name : Auth::user()->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 bg-gray-50/50 rounded-3xl border border-gray-100 flex flex-col justify-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.position') }}</span>
                        <span class="text-sm font-bold text-secondary">{{ Auth::user()->employee->position ?? __('messages.not_applicable') }}</span>
                    </div>
                    <div class="p-6 bg-gray-50/50 rounded-3xl border border-gray-100 flex flex-col justify-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.employee_id') }}</span>
                        <span class="text-sm font-bold text-secondary">#{{ Auth::user()->employee->id ?? Auth::id() }}</span>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('employee.profile.index') }}" 
                       class="flex items-center justify-center gap-3 w-full py-4 bg-secondary text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary hover:text-secondary transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ __('messages.view_full_profile') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Metrics Card -->
        <div class="group relative bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] hover:translate-y-[-8px] transition-all duration-500 overflow-hidden">
            <div class="absolute top-0 left-0 w-32 h-32 bg-secondary/5 rounded-br-[5rem] -ml-10 -mt-10 transition-transform duration-700 group-hover:scale-125"></div>
            
            <div class="relative z-10 h-full flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-6">
                        <div class="bg-primary/10 p-4 rounded-2xl border border-primary/20 shadow-inner group-hover:bg-primary transition-all duration-700">
                            <svg class="w-7 h-7 text-secondary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.406 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.406-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.latest_payslip') }}</h3>
                    </div>
                </div>
                
                <div class="flex-grow flex items-center justify-center mb-10 py-4">
                    <div class="text-center relative">
                        <span class="text-7xl font-black text-secondary tracking-tighter drop-shadow-sm">
                            @if($widgets['latest_payslip'])
                                {{ number_format($widgets['latest_payslip']->net_salary, 0) }}
                            @else
                                0.00
                            @endif
                        </span>
                        <div class="mt-2 text-xs font-bold text-gray-400 uppercase tracking-[0.3em]">{{ __('messages.net_salary_current') }}</div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-primary/20 blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-1000 -z-10"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('employee.payslips.index') }}" 
                       class="flex flex-col items-center justify-center p-6 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-secondary hover:border-secondary transition-all duration-500 group/btn">
                        <svg class="w-6 h-6 mb-2 text-primary group-hover/btn:translate-y-[-2px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <span class="text-[10px] font-bold text-secondary group-hover/btn:text-white uppercase tracking-wider text-center leading-tight">
                            {{ __('messages.view_payslips') }}
                        </span>
                    </a>
                    <a href="{{ route('employee.leaves.index') }}" 
                       class="flex flex-col items-center justify-center p-6 rounded-3xl bg-gray-50 border border-gray-100 hover:bg-secondary hover:border-secondary transition-all duration-500 group/btn">
                        <svg class="w-6 h-6 mb-2 text-primary group-hover/btn:translate-y-[-2px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-[10px] font-bold text-secondary group-hover/btn:text-white uppercase tracking-wider text-center leading-tight">
                            {{ __('messages.leave_balance') }}: {{ $widgets['leave_balance'] ?? 0 }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
        <div class="px-10 py-7 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-2 h-6 bg-primary rounded-full"></div>
                <h3 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.latest_announcements') }}</h3>
            </div>
            <a href="{{ route('employee.announcements.index') }}" class="text-xs font-black uppercase tracking-widest text-primary hover:text-primary/70 transition-colors">{{ __('messages.view_all') }}</a>
        </div>
        
        <div class="divide-y divide-gray-50">
            @forelse($widgets['recent_announcements'] as $announcement)
                <div class="p-10 hover:bg-gray-50 transition-all duration-300 group/announcement">
                    <div class="flex items-center justify-between gap-6">
                        <div class="flex-grow">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-primary/10 text-[9px] font-black text-primary uppercase tracking-widest rounded-full">{{ __('messages.notice') }}</span>
                                <span class="text-[10px] font-bold text-gray-400">{{ $announcement->published_at->translatedFormat('d M, Y') }}</span>
                            </div>
                            <h4 class="text-lg font-black text-secondary mb-2 group-hover/announcement:text-primary transition-colors leading-tight">{{ $announcement->title }}</h4>
                        </div>
                        <a href="{{ route('employee.announcements.index') }}" class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-secondary transition-all duration-300">
                            <svg class="w-5 h-5 transition-transform group-hover/announcement:translate-x-1 rtl:group-hover/announcement:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4v4h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_announcements_yet') }}</h3>
                </div>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection

