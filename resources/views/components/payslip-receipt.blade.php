@props(['payslip'])

<div class="bg-white text-gray-900 rounded-2xl shadow-xl overflow-hidden print:shadow-none print:border-none print:m-0 print:p-0">
    <!-- Header -->
    <div class="px-8 py-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-black tracking-tight text-gray-900">{{ env('APP_NAME', 'Corporate') }}</h2>
            <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">{{ __('messages.payslip_detail') }}</p>
        </div>
        <div class="text-left md:text-right">
            <p class="text-lg font-bold text-blue-600">{{ $payslip->payrollRun->month->format('F Y') }}</p>
            <p class="text-sm text-gray-500">{{ __('messages.generated_on') ?? 'Generated on' }}: {{ $payslip->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div>
            <span class="block text-xs font-bold text-gray-400 uppercase">{{ __('messages.employee_name') }}</span>
            <span class="block font-medium mt-1">{{ $payslip->employee->name }}</span>
        </div>
        <div>
            <span class="block text-xs font-bold text-gray-400 uppercase">{{ __('messages.position') }}</span>
            <span class="block font-medium mt-1">{{ $payslip->employee->position ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-xs font-bold text-gray-400 uppercase">{{ __('messages.national_id_number') ?? 'ID Number' }}</span>
            <span class="block font-medium mt-1 font-mono">{{ $payslip->employee->national_id_number ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-xs font-bold text-gray-400 uppercase">{{ __('messages.basic_salary') }}</span>
            <span class="block font-medium mt-1 font-mono">{{ number_format($payslip->basic_salary, 2) }}</span>
        </div>
    </div>

    <!-- Details Section -->
    <div class="p-0 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-8 bg-white" x-data="{ showEarnings: true, showDeductions: true }">
        
        <!-- Accordions for Mobile (Hidden on Print & md+) -->
        <div class="md:hidden print:hidden border-b border-gray-100 flex p-4 justify-between" @click="showEarnings = !showEarnings">
            <h3 class="font-bold text-gray-900">{{ __('messages.allowances') ?? 'Earnings' }}</h3>
            <svg class="w-5 h-5 transition-transform" :class="showEarnings ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>

        <!-- Earnings -->
        <div :class="{'hidden': !showEarnings, 'block': showEarnings}" class="md:block print:block px-6 py-4 md:p-0">
            <h3 class="hidden md:block print:block text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">{{ __('messages.allowances') ?? 'Earnings' }}</h3>
            <table class="w-full text-sm">
                <tbody>
                    @forelse($payslip->lineItems->where('type', 'allowance') as $item)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="py-2 text-gray-600">{{ $item->component_name }}</td>
                        <td class="py-2 text-right font-mono text-green-600">+{{ number_format($item->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="py-2 text-gray-400 italic text-xs">{{ __('messages.no_allowances') ?? 'No allowances' }}</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-100">
                        <td class="py-3 font-bold text-gray-900">{{ __('messages.total_allowances') }}</td>
                        <td class="py-3 text-right font-bold text-green-600 font-mono">{{ number_format($payslip->total_allowances, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Mobile Accordion for Deductions -->
        <div class="md:hidden print:hidden border-b border-t border-gray-100 flex p-4 justify-between bg-gray-50 bg-opacity-50" @click="showDeductions = !showDeductions">
            <h3 class="font-bold text-gray-900">{{ __('messages.deductions') ?? 'Deductions' }}</h3>
            <svg class="w-5 h-5 transition-transform" :class="showDeductions ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>

        <!-- Deductions -->
        <div :class="{'hidden': !showDeductions, 'block': showDeductions}" class="md:block print:block px-6 py-4 md:p-0">
            <h3 class="hidden md:block print:block text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">{{ __('messages.deductions') ?? 'Deductions' }}</h3>
            <table class="w-full text-sm">
                <tbody>
                    @forelse($payslip->lineItems->where('type', 'deduction') as $item)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="py-2 text-gray-600">{{ $item->component_name }}</td>
                        <td class="py-2 text-right font-mono text-red-600">-{{ number_format($item->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="py-2 text-gray-400 italic text-xs">{{ __('messages.no_deductions') ?? 'No deductions' }}</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-100">
                        <td class="py-3 font-bold text-gray-900">{{ __('messages.total_deductions') }}</td>
                        <td class="py-3 text-right font-bold text-red-600 font-mono">{{ number_format($payslip->total_deductions, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

    <!-- Final Net -->
    <div class="bg-gray-900 text-white px-8 py-6 md:rounded-b-2xl print:bg-white print:text-black print:border-t-4 print:border-black print:rounded-none flex justify-between items-center">
        <div class="font-medium text-lg text-gray-300 print:text-gray-900">{{ __('messages.net_salary') }}</div>
        <div class="text-3xl font-black font-mono tracking-tight">{{ number_format($payslip->net_salary, 2) }} <span class="text-sm font-normal text-gray-400 print:text-gray-500">SAR</span></div>
    </div>
</div>
