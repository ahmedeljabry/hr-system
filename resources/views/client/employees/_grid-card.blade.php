<div class="bg-white rounded-3xl shadow-[0_10px_30px_rgba(0,0,0,0.03)] border border-gray-100/80 p-8 flex flex-col hover:shadow-[0_20px_60px_rgba(0,0,0,0.06)] hover:translate-y-[-4px] transition-all duration-500 relative group overflow-hidden">
    <!-- Subtle background halo on hover -->
    <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

    <div class="flex flex-col items-center mb-8 relative">
        <div class="relative mb-4">
            <x-avatar :name="$employee->name" size="xl" class="shadow-lg border-2 border-white" />
            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div>
        </div>
        <h3 class="text-xl font-black text-secondary text-center group-hover:text-primary transition-colors leading-tight mb-2">{{ $employee->name }}</h3>
        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-primary/10 text-primary border border-primary/20">
            {{ $employee->position }}
        </span>
    </div>

    <div class="flex-1 space-y-4 relative">
        <div class="grid grid-cols-1 gap-3">
            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 border border-gray-100 group-hover:bg-white transition-colors duration-500">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.national_id_number') }}</span>
                <span class="text-xs font-black text-secondary font-mono tracking-tighter">{{ $employee->national_id_number }}</span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 border border-gray-100 group-hover:bg-white transition-colors duration-500">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.total_salary') }}</span>
                <span class="text-xs font-black text-secondary">{{ number_format($employee->total_salary, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3 relative">
        <a href="{{ route('client.employees.show', $employee) }}" class="flex-1 inline-flex justify-center items-center py-3 bg-gray-50 hover:bg-secondary hover:text-white text-secondary text-xs font-bold rounded-2xl transition-all duration-300">
            {{ __('messages.view') }}
        </a>
        <a href="{{ route('client.employees.edit', $employee) }}" class="flex-1 inline-flex justify-center items-center py-3 bg-primary/10 hover:bg-primary text-primary hover:text-secondary text-xs font-black rounded-2xl border border-primary/20 transition-all duration-300">
            {{ __('messages.edit') }}
        </a>
    </div>
</div>
