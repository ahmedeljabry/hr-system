<tr class="hover:bg-gray-50/50 transition-all duration-300 group border-b border-gray-50 last:border-0">
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="flex items-center">
            @php
                $displayName = app()->getLocale() == 'ar' ? $employee->name_ar : ($employee->name_en ?? $employee->name_ar);
            @endphp
            <x-avatar :name="$displayName" size="md" class="me-4 shadow-sm border border-gray-100 group-hover:scale-110 transition-transform duration-500" />
            <div class="text-sm font-black text-secondary group-hover:text-primary transition-colors">{{ $displayName }}</div>
        </div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-primary/10 text-primary border border-primary/20">
            {{ $employee->position }}
        </span>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-xs text-gray-500 font-mono tracking-tighter">{{ $employee->national_id_number }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm text-secondary font-black tracking-tight">{{ number_format($employee->total_salary, 2) }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider">{{ $employee->hire_date->format('d/m/Y') }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap text-right rtl:text-left">
        <div class="flex justify-end gap-3 transition-all duration-300">
            <a href="{{ route('client.employees.show', $employee) }}" 
               class="p-2 text-gray-400 hover:text-white hover:bg-secondary rounded-xl transition-all duration-300" 
               title="{{ __('messages.view') }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
            <a href="{{ route('client.employees.edit', $employee) }}" 
               class="p-2 text-primary hover:text-secondary hover:bg-primary rounded-xl transition-all duration-300" 
               title="{{ __('messages.edit') }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
            <form action="{{ route('client.employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_employee') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="p-2 text-red-500 hover:text-white hover:bg-red-500 rounded-xl transition-all duration-300" 
                        title="{{ __('messages.delete') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>
