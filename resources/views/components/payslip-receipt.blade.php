@props(['payslip'])

<div class="bg-white text-secondary rounded-[2rem] shadow-2xl overflow-hidden print:shadow-none print:border print:m-0 print:p-0 border border-gray-100">
    <!-- Premium Header -->
    <div class="px-10 py-10 bg-gradient-to-br from-secondary to-[#1a1a2e] relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-white">{{ env('APP_NAME', 'Corporate') }}</h2>
                <div class="flex items-center gap-3 mt-2">
                    <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                    <p class="text-xs text-primary/80 uppercase tracking-[0.3em] font-black">{{ __('messages.payslip_detail') }}</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <p class="text-2xl font-black text-white tracking-tight">{{ $payslip->payrollRun->month->translatedFormat('F Y') }}</p>
                <p class="text-xs font-bold text-white/40 mt-1 uppercase tracking-widest">{{ __('messages.generated_on') }}: {{ $payslip->created_at->translatedFormat('M d, Y') }}</p>
            </div>
        </div>
        <!-- Decorative accents -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white/5 rounded-full -ml-10 -mb-10 blur-2xl"></div>
    </div>

    <!-- Employee Profile Summary -->
    <div class="px-10 py-8 bg-gray-50/50 border-b border-gray-100 flex flex-wrap gap-8 items-center">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-secondary text-white flex items-center justify-center font-black text-lg">
                {{ substr($payslip->employee->name, 0, 1) }}
            </div>
            <div>
                <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">{{ __('messages.employee_name') }}</span>
                <span class="block font-black text-secondary">{{ $payslip->employee->name }}</span>
            </div>
        </div>
        <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
        <div>
            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">{{ __('messages.position') }}</span>
            <span class="block font-bold text-gray-700">{{ $payslip->employee->position ?? '-' }}</span>
        </div>
        <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
        <div>
            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">{{ __('messages.national_id_number') }}</span>
            <span class="block font-mono font-bold text-gray-700">{{ $payslip->employee->national_id_number ?? '-' }}</span>
        </div>
        <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
        <div>
            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">{{ __('messages.basic_salary') }}</span>
            <span class="block font-mono font-bold text-secondary">{{ number_format($payslip->basic_salary, 2) }} <small class="text-[8px] opacity-50">SAR</small></span>
        </div>
    </div>

    <!-- Financial Breakdown -->
    <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-16">
        <!-- Allowances (Earnings) -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h3 class="text-sm font-black text-secondary uppercase tracking-widest">{{ __('messages.allowances') }}</h3>
            </div>
            <div class=\"space-y-4\">
                <!-- Standard Allowances -->
                @if($payslip->housing_allowance > 0)
                <div class=\"flex justify-between items-center group\">
                    <span class=\"text-sm font-bold text-gray-500 group-hover:text-secondary transition-colors\">{{ __('messages.housing_allowance') }}</span>
                    <span class=\"text-sm font-mono font-black text-green-600\">+{{ number_format($payslip->housing_allowance, 2) }}</span>
                </div>
                @endif

                @if($payslip->transportation_allowance > 0)
                <div class=\"flex justify-between items-center group\">
                    <span class=\"text-sm font-bold text-gray-500 group-hover:text-secondary transition-colors\">{{ __('messages.transportation_allowance') }}</span>
                    <span class=\"text-sm font-mono font-black text-green-600\">+{{ number_format($payslip->transportation_allowance, 2) }}</span>
                </div>
                @endif

                @if($payslip->other_allowances > 0)
                <div class=\"flex justify-between items-center group\">
                    <span class=\"text-sm font-bold text-gray-500 group-hover:text-secondary transition-colors\">{{ __('messages.other_allowances') }}</span>
                    <span class=\"text-sm font-mono font-black text-green-600\">+{{ number_format($payslip->other_allowances, 2) }}</span>
                </div>
                @endif

                <!-- Additional Line Items -->
                @foreach($payslip->lineItems->where('type', 'allowance') as $item)
                <div class=\"flex justify-between items-center group\">
                    <span class=\"text-sm font-bold text-gray-500 group-hover:text-secondary transition-colors\">{{ $item->component_name }}</span>
                    <span class=\"text-sm font-mono font-black text-green-600\">+{{ number_format($item->amount, 2) }}</span>
                </div>
                @endforeach

                @if($payslip->total_allowances == 0 && $payslip->housing_allowance == 0 && $payslip->transportation_allowance == 0 && $payslip->other_allowances == 0)
                <div class=\"py-4 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200\">
                    <p class=\"text-xs font-bold text-gray-400 italic\">-- {{ __('messages.no_allowances') }} --</p>
                </div>
                @endif
            </div>
            
            <div class=\"pt-6 border-t border-gray-100 flex justify-between items-center\">
                <span class=\"text-xs font-black text-secondary uppercase tracking-widest\">{{ __('messages.total_allowances') }}</span>
                <span class=\"text-lg font-mono font-black text-green-600\">{{ number_format($payslip->total_allowances, 2) }}</span>
            </div>
        </div>

        <!-- Deductions -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                </div>
                <h3 class="text-sm font-black text-secondary uppercase tracking-widest">{{ __('messages.deductions') }}</h3>
            </div>
            <div class="space-y-4">
                @forelse($payslip->lineItems->where('type', 'deduction') as $item)
                <div class="flex justify-between items-center group">
                    <span class="text-sm font-bold text-gray-500 group-hover:text-secondary transition-colors">{{ $item->component_name }}</span>
                    <span class="text-sm font-mono font-black text-red-600">-{{ number_format($item->amount, 2) }}</span>
                </div>
                @empty
                <div class="py-4 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-xs font-bold text-gray-400 italic">-- {{ __('messages.no_deductions') }} --</p>
                </div>
                @endforelse
            </div>
            @if($payslip->lineItems->where('type', 'deduction')->count() > 0)
            <div class="pt-6 border-t border-gray-100 flex justify-between items-center">
                <span class="text-xs font-black text-secondary uppercase tracking-widest">{{ __('messages.total_deductions') }}</span>
                <span class="text-lg font-mono font-black text-red-600">{{ number_format($payslip->total_deductions, 2) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Final Net Total -->
    <div class="bg-secondary p-10 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
        <div class="relative z-10">
            <span class="block text-[10px] font-black text-primary uppercase tracking-[0.4em] mb-1">{{ __('messages.net_salary') }}</span>
            <div class="text-4xl md:text-5xl font-black text-white tracking-tighter font-mono">
                {{ number_format($payslip->net_salary, 2) }} 
                <span class="text-base font-bold text-primary/40 ml-2">SAR</span>
            </div>
        </div>
        <div class="relative z-10 w-full md:w-auto">
            <div class="px-8 py-4 bg-white/5 backdrop-blur-sm rounded-3xl border border-white/10 text-center">
                <span class="text-[10px] font-black text-white/40 uppercase tracking-widest block mb-1">Payment Status</span>
                <span class="text-sm font-black text-primary uppercase">Confirmed & Paid</span>
            </div>
        </div>
        <!-- Decorative accents -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-primary/5 to-transparent"></div>
    </div>
</div>
