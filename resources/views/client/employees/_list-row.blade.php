<tr class="hover:bg-gray-50/50 transition-all duration-300">
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="flex items-center gap-4">
            <x-avatar :name="app()->getLocale() == 'ar' ? $employee->name_ar : ($employee->name_en ?? $employee->name_ar)" size="md" class="rounded-2xl shadow-sm border border-gray-100" />
            <div>
                <div class="text-base font-black text-secondary tracking-tight capitalize">{{ app()->getLocale() == 'ar' ? $employee->name_ar : ($employee->name_en ?? $employee->name_ar) }}</div>
                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $employee->position }}</div>
            </div>
        </div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-500 bg-gray-50 inline-block px-3 py-1 rounded-xl border border-gray-100">{{ $employee->position }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-500 font-mono">{{ $employee->national_id_number }}</div>
    </td>
    <td class="px-8 py-6 whitespace-nowrap">
        <div class="text-sm font-black text-secondary">{{ number_format($employee->basic_salary, 2) }} <span class="text-[10px] text-gray-400 ms-0.5 uppercase">SAR</span></div>
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
            
            <form id="delete-row-{{ $employee->id }}" action="{{ route('client.employees.destroy', $employee->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" 
                    onclick="if(confirm('{{ __('messages.confirm_delete_employee') }}')) document.getElementById('delete-row-{{ $employee->id }}').submit()"
                    class="inline-flex items-center gap-2 px-3 py-1.5 text-red-600 hover:text-white hover:bg-red-500 rounded-xl transition-all duration-300 border border-red-500/10 hover:border-red-500 font-bold text-xs bg-red-50/50"
                    title="{{ __('messages.delete') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                <span>{{ __('messages.delete') }}</span>
            </button>
        </div>
    </td>
</tr>
