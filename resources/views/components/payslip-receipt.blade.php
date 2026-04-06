@props(['payslip'])

@php
    $isRtl = app()->getLocale() === 'ar';
    $monthName = $payslip->payrollRun->month->translatedFormat('F Y');
    $generationDate = $payslip->created_at->translatedFormat('M d, Y');
@endphp

<div dir="{{ $isRtl ? 'rtl' : 'ltr' }}" class="bg-white text-secondary rounded-[2.5rem] shadow-[0_30px_70px_rgba(0,0,0,0.08)] overflow-hidden print:shadow-none print:border border border-gray-100 max-w-full">
    <!-- Premium Header -->
    <div class="px-12 py-12 bg-gradient-to-br from-secondary to-[#0f172a] relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
            <div class="space-y-2">
                <h2 class="text-4xl font-black tracking-tighter text-white">{{ env('APP_NAME', 'Corporate') }}</h2>
                <div class="flex items-center gap-3">
                    <div class="flex space-x-1 rtl:space-x-reverse">
                        <span class="w-1.5 h-1.5 bg-primary rounded-full animate-ping"></span>
                        <span class="w-1.5 h-1.5 bg-primary/40 rounded-full"></span>
                    </div>
                    <p class="text-[10px] text-primary tracking-[0.4em] font-black uppercase">{{ __('messages.payslip_detail') }}</p>
                </div>
            </div>
            <div class="text-{{ $isRtl ? 'right' : 'left' }} md:text-{{ $isRtl ? 'left' : 'right' }}">
                <p class="text-3xl font-black text-white tracking-tight">{{ $monthName }}</p>
                <p class="text-[10px] font-bold text-white/30 mt-2 uppercase tracking-widest">{{ __('messages.generated_on') }}: {{ $generationDate }}</p>
            </div>
        </div>
        <!-- Abstract Shapes -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="absolute bottom-[-5rem] left-[-5rem] w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
    </div>

    <!-- Personnel Highlights -->
    <div class="px-12 py-10 bg-gray-50/70 border-b border-gray-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-secondary text-white flex items-center justify-center font-black text-xl shadow-lg shadow-secondary/10">
                {{ mb_substr($payslip->employee->name, 0, 1) }}
            </div>
            <div>
                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.employee_name') }}</span>
                <span class="block font-black text-secondary text-lg leading-tight">{{ $payslip->employee->name }}</span>
            </div>
        </div>
        <div>
            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.position') }}</span>
            <span class="block font-bold text-gray-700">{{ $payslip->employee->position ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.national_id_number') }}</span>
            <span class="block font-mono font-bold text-gray-600 tracking-wider">{{ $payslip->employee->national_id_number ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('messages.basic_salary') }}</span>
            <span class="block font-mono font-black text-secondary text-xl">
                {{ number_format($payslip->basic_salary, 2) }}
                <span class="text-[10px] font-bold text-gray-400 uppercase ml-1 rtl:mr-1">{{ __('messages.currency_sar') }}</span>
            </span>
        </div>
    </div>

    <!-- Detailed Ledger -->
    <div class="p-12 grid grid-cols-1 lg:grid-cols-2 gap-20">
        <!-- Earnings -->
        <div class="space-y-8">
            <div class="flex items-center justify-between border-b border-gray-100 pb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="font-black text-secondary uppercase tracking-widest">{{ __('messages.allowances') }}</h3>
                </div>
            </div>
            <div class="space-y-5">
                @forelse($payslip->lineItems->where('type', 'allowance') as $item)
                <div class="flex justify-between items-center group">
                    <span class="text-sm font-bold text-gray-500 group-hover:text-secondary transition-colors">{{ $item->component_name }}</span>
                    <span class="text-sm font-mono font-black text-green-600 tabular-nums">+{{ number_format($item->amount, 2) }}</span>
                </div>
                @empty
                <div class="py-10 text-center bg-gray-50/50 rounded-[2rem] border border-dashed border-gray-200">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest opacity-60">-- {{ __('messages.no_allowances') }} --</p>
                </div>
                @endforelse
            </div>
            @if($payslip->lineItems->where('type', 'allowance')->count() > 0)
            <div class="pt-8 border-t-2 border-gray-100 flex justify-between items-center">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.total_allowances') }}</span>
                <span class="text-2xl font-mono font-black text-green-600">{{ number_format($payslip->total_allowances, 2) }}</span>
            </div>
            @endif
        </div>

        <!-- Deductions -->
        <div class="space-y-8">
            <div class="flex items-center justify-between border-b border-gray-100 pb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                    </div>
                    <h3 class="font-black text-secondary uppercase tracking-widest">{{ __('messages.deductions') }}</h3>
                </div>
            </div>
            <div class="space-y-5">
                @forelse($payslip->lineItems->where('type', 'deduction') as $item)
                <div class="flex justify-between items-center group">
                    <span class="text-sm font-bold text-gray-500 group-hover:text-secondary transition-colors">{{ $item->component_name }}</span>
                    <span class="text-sm font-mono font-black text-red-600 tabular-nums">-{{ number_format($item->amount, 2) }}</span>
                </div>
                @empty
                <div class="py-10 text-center bg-gray-50/50 rounded-[2rem] border border-dashed border-gray-200">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest opacity-60">-- {{ __('messages.no_deductions') }} --</p>
                </div>
                @endforelse
            </div>
            @if($payslip->lineItems->where('type', 'deduction')->count() > 0)
            <div class="pt-8 border-t-2 border-gray-100 flex justify-between items-center">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.total_deductions') }}</span>
                <span class="text-2xl font-mono font-black text-red-600">{{ number_format($payslip->total_deductions, 2) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Final Net Settlement -->
    <div class="bg-secondary p-12 flex flex-col md:flex-row justify-between items-center gap-10 relative overflow-hidden">
        <div class="relative z-10 w-full md:w-auto">
            <span class="block text-[10px] font-black text-primary uppercase tracking-[0.5em] mb-3">{{ __('messages.net_salary') }}</span>
            <div class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tighter leading-none break-all max-w-full">
                {{ number_format(min($payslip->net_salary ?? 0, 99999999.99), 2) }} 
                <span class="text-lg font-bold text-primary opacity-40 ml-4 rtl:mr-4">{{ __('messages.currency_sar') }}</span>
            </div>
        </div>
        <div class="relative z-10 w-full md:w-auto">
            <div class="px-10 py-6 bg-white/5 backdrop-blur-xl rounded-[2rem] border border-white/10 text-center shadow-inner">
                <span class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2">{{ __('messages.payment_status_label') }}</span>
                <div class="flex items-center justify-center gap-3">
                    <span class="w-3 h-3 bg-primary rounded-full shadow-[0_0_15px_rgba(0,77,230,0.5)]"></span>
                    <span class="text-base font-black text-white uppercase tracking-widest">{{ __('messages.confirmed_and_paid') }}</span>
                </div>
            </div>
        </div>
        <!-- Decorative accents -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(0,0,230,0.05),transparent)]"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-white/5 to-transparent skew-x-12"></div>
    </div>
</div>
