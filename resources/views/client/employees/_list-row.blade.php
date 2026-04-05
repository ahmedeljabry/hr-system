<tr class="hover:bg-gray-50 transition-colors group">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <x-avatar :name="$employee->name" size="md" class="me-3" />
            <div class="text-sm font-semibold text-gray-900">{{ $employee->name }}</div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
            {{ $employee->position }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-600 font-mono">{{ $employee->national_id_number }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900 font-medium">{{ number_format($employee->basic_salary, 2) }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-600">{{ $employee->hire_date->format('d/m/Y') }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium rtl:text-left opacity-0 group-hover:opacity-100 transition-opacity flex justify-end gap-2">
        <a href="{{ route('client.employees.show', $employee) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded-md transition-colors" title="{{ __('messages.view') }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
        </a>
        <a href="{{ route('client.employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-1.5 rounded-md transition-colors" title="{{ __('messages.edit') }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        </a>
    </td>
</tr>
