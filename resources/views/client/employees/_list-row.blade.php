<tr class="hover:bg-gray-50/50 transition-all duration-300">
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="flex items-center gap-4">
            <x-avatar :name="$employee->name" size="md" class="rounded-2xl shadow-sm border border-gray-100" />
            <div>
                <div class="text-base font-black text-secondary tracking-tight capitalize">{{ $employee->name }}</div>
                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $employee->position }}</div>
            </div>
        </div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-500 bg-gray-50 inline-block px-3 py-1 rounded-xl border border-gray-100">{{ $employee->position }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-black text-secondary uppercase">{{ $employee->nationality ?: 'Saudi' }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-500 font-mono">{{ $employee->national_id_number }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-black text-secondary">{{ number_format($employee->basic_salary, 2) }} <span class="text-[10px] text-gray-400 ms-0.5 uppercase">{{ __('messages.currency_sar') }}</span></div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-500">{{ $employee->hire_date->format('d/m/Y') }}</div>
    </td>
    <td class="px-8 py-6 text-end">
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('client.employees.show', $employee) }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 text-secondary hover:bg-secondary/5 rounded-xl transition-all duration-300 border border-gray-100 font-bold text-xs"
               title="{{ __('messages.view') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <span>{{ __('messages.view') }}</span>
            </a>
            <a href="{{ route('client.employees.edit', $employee) }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 text-primary hover:bg-primary/10 rounded-xl transition-all duration-300 border border-primary/10 font-bold text-xs"
               title="{{ __('messages.edit') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                <span>{{ __('messages.edit') }}</span>
            </a>
            
            <a href="{{ route('client.employees.terminate.form', $employee->id) }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 text-red-600 hover:text-white hover:bg-red-500 rounded-xl transition-all duration-300 border border-red-500/10 hover:border-red-500 font-bold text-xs bg-red-50/50"
               title="{{ __('messages.terminate') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                </svg>
                <span>{{ __('messages.terminate') }}</span>
            </a>
        </div>
    </td>
</tr>
