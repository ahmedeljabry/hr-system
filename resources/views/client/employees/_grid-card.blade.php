<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md transition-shadow relative">
    <div class="absolute top-4 right-4 rtl:left-4 rtl:right-auto">
        <!-- Optional Badges or Actions -->
    </div>
    
    <div class="flex flex-col items-center mb-4">
        <x-avatar :name="$employee->name" size="lg" class="mb-3" />
        <h3 class="text-lg font-bold text-gray-900 text-center">{{ $employee->name }}</h3>
        <p class="text-xs text-primary font-medium bg-blue-50 px-3 py-1 rounded-full mt-1">{{ $employee->position }}</p>
    </div>

    <div class="flex-1">
        <div class="flex flex-col space-y-2 mt-4 text-sm text-gray-600">
            <div class="flex justify-between items-center py-1 border-b border-gray-50">
                <span class="text-gray-400">{{ __('messages.national_id_number') }}</span>
                <span class="font-medium text-gray-900 font-mono">{{ $employee->national_id_number }}</span>
            </div>
            <div class="flex justify-between items-center py-1 border-b border-gray-50">
                <span class="text-gray-400">{{ __('messages.basic_salary') }}</span>
                <span class="font-medium text-gray-900">{{ number_format($employee->basic_salary, 2) }}</span>
            </div>
            <div class="flex justify-between items-center py-1">
                <span class="text-gray-400">{{ __('messages.hire_date') }}</span>
                <span class="font-medium text-gray-900">{{ $employee->hire_date->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center space-x-2 rtl:space-x-reverse">
        <a href="{{ route('client.employees.show', $employee) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-lg transition-colors">
            {{ __('messages.view') }}
        </a>
        <a href="{{ route('client.employees.edit', $employee) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-primary/10 hover:bg-primary/20 text-primary text-sm font-medium rounded-lg transition-colors">
            {{ __('messages.edit') }}
        </a>
    </div>
</div>
