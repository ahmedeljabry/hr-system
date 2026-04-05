@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="w-full">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.payroll_history') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('messages.payroll_desc') }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.payroll.create') }}" 
                       class="inline-flex items-center px-10 py-4 bg-primary hover:bg-[#8affaa] text-secondary text-sm font-black rounded-2xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/add">
                        <svg class="w-5 h-5 me-3 group-hover/add:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('messages.run_payroll') }}
                    </a>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-100 p-5 rounded-2xl shadow-sm flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-green-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 bg-red-50 border border-red-100 p-5 rounded-2xl shadow-sm flex items-center gap-4">
                <div class="bg-red-100 p-2 rounded-xl">
                    <svg class="h-6 w-6 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden min-h-[600px] flex flex-col transition-all duration-500">
            <div class="overflow-x-auto p-1">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.select_month') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.employee_count') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.total_net_payout') }}</th>
                            <th class="px-8 py-6 text-start text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.run_date') }}</th>
                            <th class="px-8 py-6 text-end text-xs font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($runs as $run)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300 group cursor-pointer" onclick="window.location='{{ route('client.payroll.show', $run->id) }}'">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center me-4 group-hover:bg-primary transition-colors duration-500">
                                            <svg class="w-5 h-5 text-secondary group-hover:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-sm font-black text-secondary group-hover:text-primary transition-colors">{{ $run->month->format('M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    @if($run->isConfirmed())
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-green-50 text-green-600 border border-green-100">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full me-2 animate-pulse"></span>
                                            {{ __('messages.payroll_confirmed') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full me-2"></span>
                                            {{ __('messages.payroll_draft') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-sm font-bold text-gray-500">
                                    {{ $run->payslips_count }}
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-black text-secondary">{{ number_format($run->payslips->sum('net_salary'), 2) }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $run->created_at->format('Y-m-d H:i') }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-end rtl:text-left">
                                    <div class="flex justify-end opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                                        <a href="{{ route('client.payroll.show', $run->id) }}" class="p-2 text-primary hover:text-secondary hover:bg-primary rounded-xl transition-all duration-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-32 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <div class="bg-gray-100 p-8 rounded-[2.5rem] mb-6">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.406 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.406-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-secondary mb-2">{{ __('messages.no_payroll_runs') }}</h3>
                                        <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_payroll_desc') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            @if($runs->hasPages())
                <div class="p-8 border-t border-gray-50 bg-gray-50/20">
                    {{ $runs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
