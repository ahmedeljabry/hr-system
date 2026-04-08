<div class="group bg-white rounded-[2.5rem] shadow-[0_15px_50px_rgba(0,0,0,0.03)] border border-gray-100 p-2 flex flex-col transition-all duration-700 hover:shadow-[0_30px_80px_rgba(0,0,0,0.08)] hover:-translate-y-2 relative overflow-hidden h-full min-h-[420px]">
    <!-- Decorative Corner Element -->
    <div class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-bl-[5rem] translate-x-10 -translate-y-10 group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-700"></div>
    
    <div class="p-6 flex flex-col h-full">
        <!-- Avatar & Status Header -->
        <div class="flex items-start justify-between mb-8">
            <div class="relative">
                <div class="absolute inset-0 bg-primary/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="relative bg-gray-50 rounded-3xl p-1 border-2 border-white shadow-sm overflow-hidden group-hover:rotate-3 transition-transform duration-500">
                    <x-avatar :name="$employee->name" size="xl" class="rounded-2xl" />
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $employee->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $employee->status === 'active' ? 'bg-green-500' : 'bg-red-500' }} me-1.5 animate-pulse"></span>
                    {{ $employee->status ?? 'Active' }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-600 border border-gray-200">
                    {{ $employee->nationality ?: 'Saudi' }}
                </span>
            </div>
        </div>

        <!-- Name & Position -->
        <div class="mb-8">
            <h3 class="text-2xl font-black text-secondary leading-tight mb-2 group-hover:text-primary transition-colors duration-300">
                {{ $employee->name }}
            </h3>
            <div class="flex items-center text-gray-400 font-bold text-sm">
                <svg class="w-4 h-4 me-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ $employee->position }}
            </div>
        </div>

        <!-- Meta Info Grid -->
        <div class="space-y-3 mb-8">
            <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50/50 border border-gray-100/50 group-hover:bg-white transition-colors duration-500">
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">{{ __('messages.total_salary') }}</span>
                    <span class="text-sm font-black text-secondary">{{ number_format($employee->total_salary, 2) }} <span class="text-[10px] text-gray-400 ms-1 uppercase">{{ __('messages.currency_sar') }}</span></span>
                </div>
                <div class="bg-white p-2 rounded-xl shadow-sm text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-auto pt-6 flex flex-col sm:flex-row gap-3 border-t border-gray-50 items-center">
            <a href="{{ route('client.employees.show', $employee) }}" 
               class="flex-1 w-full inline-flex items-center justify-center px-4 py-3 bg-secondary text-white text-xs font-black rounded-2xl transition-all duration-300 hover:shadow-lg active:scale-95 group/view">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                {{ __('messages.view') }}
            </a>
            <a href="{{ route('client.employees.edit', $employee) }}" 
               class="inline-flex items-center justify-center p-3 bg-primary/10 text-primary border border-primary/20 rounded-2xl hover:bg-primary hover:text-secondary transition-all duration-300 hover:-translate-y-1 active:translate-y-0"
               title="{{ __('messages.edit') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
            
            <a href="{{ route('client.employees.terminate.form', $employee->id) }}" 
               class="inline-flex items-center justify-center p-3 text-red-600 hover:text-white hover:bg-red-500 rounded-2xl transition-all duration-300 border border-red-500/10 hover:border-red-500 font-bold bg-red-50/30"
               title="{{ __('messages.terminate') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                </svg>
            </a>
        </div>
    </div>
</div>
