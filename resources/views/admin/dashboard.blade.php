@extends('layouts.admin')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.super_admin_dashboard') ?? 'Super Admin Dashboard'" 
            :subtitle="__('messages.system_overview_management') ?? 'System overview and management tools'"
        />

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">
            <x-sparkline-card 
                id="clients-sparkline"
                title="{{ __('messages.total_clients') ?? 'Total Clients' }}" 
                value="{{ number_format($stats['total_clients']) }}"
                data="{{ json_encode($trends['clients']) }}"
                color="#142533"
            />

            <x-sparkline-card 
                id="employees-sparkline"
                title="{{ __('messages.total_employees') ?? 'Total Employees' }}" 
                value="{{ number_format($stats['total_employees']) }}"
                data="{{ json_encode($trends['employees']) }}"
                color="#10B981"
            />

            <div class="group relative bg-white p-8 rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/50 hover:shadow-[0_25px_60px_rgba(34,197,94,0.1)] hover:translate-y-[-4px] transition-all duration-500 overflow-hidden">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.active_subscriptions') ?? 'Active' }}</span>
                        <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-secondary">{{ number_format($stats['active_count']) }}</p>
                </div>
            </div>

            <div class="group relative bg-white p-8 rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/50 hover:shadow-[0_25px_60px_rgba(245,158,11,0.1)] hover:translate-y-[-4px] transition-all duration-500 overflow-hidden">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.suspended_subscriptions') ?? 'Suspended' }}</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-secondary">{{ number_format($stats['suspended_count']) }}</p>
                </div>
            </div>

            <div class="group relative bg-white p-8 rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/50 hover:shadow-[0_25px_60px_rgba(239,68,68,0.1)] hover:translate-y-[-4px] transition-all duration-500 overflow-hidden">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.expired_subscriptions') ?? 'Expired' }}</span>
                        <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-secondary">{{ number_format($stats['expired_count']) }}</p>
                </div>
            </div>
        </div>

        <!-- System Sections -->
        <div class="grid grid-cols-1 gap-10">
            <!-- Clients Management Card -->
            <a href="{{ route('admin.clients.index') }}" class="group relative bg-white p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 hover:shadow-[0_40px_80px_rgba(0,0,0,0.06)] hover:translate-y-[-8px] transition-all duration-500 overflow-hidden block">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-bl-[10rem] -mr-20 -mt-20 transition-transform duration-700 group-hover:scale-125"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-10">
                    <div class="flex items-center gap-10">
                        <div class="bg-primary/10 p-6 rounded-3xl border border-primary/20 shadow-inner group-hover:bg-primary transition-all duration-700">
                            <svg class="w-12 h-12 text-secondary group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('messages.system_management') ?? 'System Management' }}</h3>
                            <p class="text-4xl font-black text-secondary leading-none">{{ __('messages.clients_management') ?? 'Clients' }}</p>
                            <p class="mt-4 text-gray-500 font-medium max-w-xl">{{ __('messages.manage_client_subscriptions_description') ?? 'Manage client subscriptions, view organization details, and control system access across all registered companies.' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-primary font-black uppercase text-xs tracking-widest gap-2 group-hover:gap-4 transition-all pr-4">
                        <span>{{ __('messages.view_all_clients') ?? 'Manage Clients' }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection