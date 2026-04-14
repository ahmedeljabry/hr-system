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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Clients Management Card -->
            <a href="{{ route('admin.clients.index') }}" class="group relative bg-white p-8 rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/50 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:translate-y-[-6px] transition-all duration-500 overflow-hidden block">
                <div class="absolute top-0 right-0 w-48 h-48 bg-primary/5 rounded-bl-[8rem] -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-125"></div>
                <div class="relative z-10 flex flex-col justify-between h-full gap-8">
                    <div class="flex items-start justify-between">
                        <div class="bg-primary/10 p-5 rounded-2xl border border-primary/20 shadow-inner group-hover:bg-primary transition-all duration-700">
                            <svg class="w-8 h-8 text-secondary group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-400 group-hover:bg-primary group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5 rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('messages.system_management') ?? 'System Management' }}</h3>
                        <p class="text-3xl font-black text-secondary leading-none mb-3">{{ __('messages.clients') ?? 'Clients' }}</p>
                        <p class="text-sm text-gray-500 font-medium leading-relaxed">{{ __('messages.manage_client_subscriptions_description') ?? 'Manage client subscriptions, view organization details, and control system access across all registered companies.' }}</p>
                    </div>
                </div>
            </a>

            <!-- Global Employees Card -->
            <a href="{{ route('admin.employees.index') }}" class="group relative bg-white p-8 rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/50 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:translate-y-[-6px] transition-all duration-500 overflow-hidden block">
                <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-500/5 rounded-bl-[8rem] -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-125"></div>
                <div class="relative z-10 flex flex-col justify-between h-full gap-8">
                    <div class="flex items-start justify-between">
                        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-inner group-hover:bg-emerald-500 transition-all duration-700">
                            <svg class="w-8 h-8 text-emerald-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-400 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5 rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('messages.global_database') ?? 'Global Database' }}</h3>
                        <p class="text-3xl font-black text-secondary leading-none mb-3">{{ __('messages.all_employees') ?? 'All Employees' }}</p>
                        <p class="text-sm text-gray-500 font-medium leading-relaxed">{{ __('messages.manage_all_employees_desc') ?? 'Browse, search, and view detailed records for all employees across every registered client in the system.' }}</p>
                    </div>
                </div>
            </a>

            <!-- Insurance Companies Card -->
            <a href="{{ route('admin.insurance-companies.index') }}" class="group relative bg-white p-8 rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/50 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:translate-y-[-6px] transition-all duration-500 overflow-hidden block">
                <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/5 rounded-bl-[8rem] -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-125"></div>
                <div class="relative z-10 flex flex-col justify-between h-full gap-8">
                    <div class="flex items-start justify-between">
                        <div class="bg-blue-50 p-5 rounded-2xl border border-blue-100 shadow-inner group-hover:bg-blue-500 transition-all duration-700">
                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-400 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5 rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('messages.system_config') ?? 'System Configuration' }}</h3>
                        <p class="text-3xl font-black text-secondary leading-none mb-3">{{ __('messages.insurance_companies') ?? 'Insurance Companies' }}</p>
                        <p class="text-sm text-gray-500 font-medium leading-relaxed">{{ __('messages.manage_insurance_companies_desc') ?? 'Manage global medical insurance providers available for clients to assign to their employees policies.' }}</p>
                    </div>
                </div>
            </a>

            <!-- Localization Decisions Card -->
            <a href="{{ route('admin.localization.index') }}" class="group relative bg-white p-8 rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-gray-100/50 hover:shadow-[0_30px_60px_rgba(0,0,0,0.06)] hover:translate-y-[-6px] transition-all duration-500 overflow-hidden block">
                <div class="absolute top-0 right-0 w-48 h-48 bg-purple-500/5 rounded-bl-[8rem] -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-125"></div>
                <div class="relative z-10 flex flex-col justify-between h-full gap-8">
                    <div class="flex items-start justify-between">
                        <div class="bg-purple-50 p-5 rounded-2xl border border-purple-100 shadow-inner group-hover:bg-purple-500 transition-all duration-700">
                            <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-gray-400 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5 rtl:-scale-x-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">{{ __('messages.saudization_rules') ?? 'Saudization Rules' }}</h3>
                        <p class="text-3xl font-black text-secondary leading-none mb-3">{{ __('messages.localization_decisions') ?? 'Localization Decisions' }}</p>
                        <p class="text-sm text-gray-500 font-medium leading-relaxed">{{ __('messages.localization_decisions_desc') ?? 'Configure and enforce Saudization mandates and ministerial decisions tracking compliance across all clients.' }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection