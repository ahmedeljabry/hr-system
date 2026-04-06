@extends('layouts.employee')

@section('content')
<div class="space-y-10">
    <!-- Premium Hero Section -->
    <div class="bg-secondary rounded-[2.5rem] shadow-xl p-10 text-white relative overflow-hidden group">
        <div class="relative z-10 text-center md:text-left {{ app()->getLocale() == 'ar' ? 'md:text-right' : '' }}">
            <h1 class="text-4xl font-black mb-2 tracking-tight">{{ __('My Payslips') }}</h1>
            <p class="text-primary text-lg opacity-90 font-medium">{{ __('View and download your monthly salary records.') }}</p>
        </div>
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 text-white opacity-10 group-hover:scale-110 transition-transform duration-1000">
            <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
        </div>
    </div>

    <!-- Payslips Table Container -->
    <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Month') }} / {{ __('Year') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Basic Salary') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Net Salary') }}
                        </th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payslips as $payslip)
                        <tr class="hover:bg-blue-50/20 transition-all duration-300 group/row cursor-pointer" onclick="window.location='{{ route('employee.payslips.show', $payslip->id) }}'">
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover/row:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div class="text-base font-black text-secondary tracking-tight group-hover/row:text-blue-600 transition-colors">
                                        {{ $payslip->payrollRun->month->translatedFormat('F Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-sm font-bold text-gray-500 font-mono tracking-tight">
                                {{ number_format($payslip->basic_salary, 2) }}
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                <span class="text-lg font-black text-blue-600 font-mono tracking-tight">
                                    {{ number_format($payslip->net_salary, 2) }}
                                </span>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-center">
                                <a href="{{ route('employee.payslips.show', $payslip->id) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-700 transition-colors">
                                    {{ __('messages.view') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('No payslips found.') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('Monthly salary records will appear here once released.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($payslips->hasPages())
        <div class="mt-8 px-10">
            {{ $payslips->links() }}
        </div>
    @endif
</div>
@endsection

