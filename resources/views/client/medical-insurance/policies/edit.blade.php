@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12" x-data="{ 
    assignedEmployees: {{ json_encode($policy->employees->map(fn($e) => ['id' => $e->id, 'name' => $e->name, 'position' => $e->position, 'class' => $e->pivot->insurance_class, 'cost' => $e->pivot->cost])) }},
    searchEmployee: '',
    employees: {{ json_encode($employees->map(fn($e) => ['id' => $e->id, 'name' => $e->name, 'national_id' => $e->national_id_number, 'position' => $e->position])) }},
    
    addEmployee(emp) {
        if (!this.assignedEmployees.find(e => e.id === emp.id)) {
            this.assignedEmployees = [...this.assignedEmployees, {
                id: emp.id,
                name: emp.name,
                position: emp.position,
                class: '',
                cost: 0
            }];
        }
    },
    removeEmployee(index) {
        this.assignedEmployees.splice(index, 1);
    },
    selectAll() {
        this.employees.forEach(emp => {
            if (!this.assignedEmployees.find(ae => ae.id === emp.id)) {
                this.addEmployee(emp);
            }
        });
    },
    get filteredEmployees() {
        const search = this.searchEmployee.toLowerCase();
        return this.employees.filter(e => {
            const name = (e.name || '').toLowerCase();
            const id = String(e.national_id || '');
            const isMatch = name.includes(search) || id.includes(search);
            const isNotAssigned = !this.assignedEmployees.find(ae => ae.id === e.id);
            return isMatch && isNotAssigned;
        });
    }
}">
    <div class="max-w-5xl mx-auto">
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.edit_policy')" 
            :subtitle="__('messages.insurance_policy') . ': ' . $policy->policy_number"
            :backLink="route('client.medical-insurance.policies.index', ['client_slug' => request('client_slug')])"
        >
            <x-slot name="leading">
                <div class="w-16 h-16 bg-primary/20 rounded-[1.5rem] flex items-center justify-center shrink-0 border border-primary/30 shadow-2xl">
                    <svg class="w-9 h-9 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
            </x-slot>
        </x-dashboard-sub-header>

        <form action="{{ route('client.medical-insurance.policies.update', ['client_slug' => request('client_slug'), 'medical_insurance_policy' => $policy->id]) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Policy Info -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h2 class="text-xl font-black text-secondary mb-6 border-b pb-4">{{ __('messages.view_details') }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.insurance_company') }} <span class="text-primary">*</span></label>
                        <select name="insurance_company_id" required class="block w-full px-5 py-4 text-sm font-bold border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-primary/30 transition-all bg-gray-50 focus:bg-white text-secondary">
                            <option value="">{{ __('messages.select_company') }}</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ $policy->insurance_company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.policy_number') }} <span class="text-primary">*</span></label>
                        <input type="text" name="policy_number" value="{{ $policy->policy_number }}" required class="block w-full px-5 py-4 text-sm font-bold border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-primary/30 transition-all bg-gray-50 focus:bg-white text-secondary">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.insurance_start_date') }} <span class="text-primary">*</span></label>
                        <input type="date" name="start_date" value="{{ $policy->start_date->format('Y-m-d') }}" required class="block w-full px-5 py-4 text-sm font-bold border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-primary/30 transition-all bg-gray-50 focus:bg-white text-secondary">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('messages.insurance_end_date') }} <span class="text-primary">*</span></label>
                        <input type="date" name="end_date" value="{{ $policy->end_date->format('Y-m-d') }}" required class="block w-full px-5 py-4 text-sm font-bold border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-primary/30 transition-all bg-gray-50 focus:bg-white text-secondary">
                    </div>


                </div>
            </div>

            <!-- Dynamic Employee Selection -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6 border-b pb-4">
                    <h2 class="text-xl font-black text-secondary">{{ __('messages.select_employees') }}</h2>
                    <button type="button" @click="selectAll()" class="px-4 py-2 bg-primary/10 text-primary-dark text-xs font-black rounded-xl hover:bg-primary/20 transition-all">
                        {{ __('messages.select_all') }}
                    </button>
                </div>
                
                <div class="mb-8">
                    <div class="relative group mb-4">
                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-6' : 'left-0 pl-6' }} flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" x-model="searchEmployee" 
                               class="block w-full {{ app()->getLocale() == 'ar' ? 'pr-14' : 'pl-14' }} py-4 text-sm font-medium border-2 border-gray-100 bg-gray-50 rounded-2xl focus:ring-0 focus:border-primary/30 focus:bg-white transition-all placeholder:text-gray-300" 
                               placeholder="{{ __('messages.search_employees') }}">
                    </div>

                    <!-- Available Employees List -->
                    <div class="border-2 border-gray-100 rounded-2xl overflow-hidden bg-gray-50/30">
                        <div class="max-h-60 overflow-y-auto divide-y divide-gray-100">
                            <template x-for="emp in filteredEmployees" :key="emp.id">
                                <button type="button" @click="addEmployee(emp)" class="w-full px-6 py-4 flex items-center justify-between hover:bg-white transition-all group">
                                    <div class="flex flex-col items-start text-start">
                                        <span class="text-sm font-black text-secondary group-hover:text-primary transition-colors" x-text="emp.name"></span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="emp.position"></span>
                                    </div>
                                    <span class="text-[10px] font-black p-2 bg-gray-100 text-gray-400 rounded-lg uppercase tracking-widest group-hover:bg-primary/20 group-hover:text-primary-dark transition-all">{{ __('messages.add') }}</span>
                                </button>
                            </template>
                            <div x-show="filteredEmployees.length === 0" class="p-8 text-center bg-white/50">
                                <span class="text-xs font-bold text-gray-400">{{ __('messages.no_search_results') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Employees Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full" x-show="assignedEmployees.length > 0">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-start text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.employee_name') }}</th>
                                <th class="px-6 py-4 text-start text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.insurance_class') }}</th>
                                <th class="px-6 py-4 text-start text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.employee_cost') }}</th>
                                <th class="px-6 py-4 text-end text-[10px] font-black text-gray-400 uppercase tracking-widest"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <template x-for="(emp, index) in assignedEmployees" :key="emp.id">
                                <tr class="group hover:bg-gray-50/30 transition-all">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-secondary" x-text="emp.name"></span>
                                            <span class="text-[10px] font-bold text-gray-400" x-text="emp.position"></span>
                                            <input type="hidden" :name="'assigned_employees[' + index + '][id]'" :value="emp.id">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" :name="'assigned_employees[' + index + '][class]'" x-model="emp.class" required
                                               class="w-32 px-4 py-2 text-xs font-bold border-2 border-gray-100 rounded-xl focus:ring-0 focus:border-primary/30 transition-all bg-gray-50 focus:bg-white"
                                               placeholder="Plan A">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <input type="number" :name="'assigned_employees[' + index + '][cost]'" x-model="emp.cost" required step="0.01" min="0"
                                                   class="w-32 px-4 py-2 text-xs font-bold border-2 border-gray-100 rounded-xl focus:ring-0 focus:border-primary/30 transition-all bg-gray-50 focus:bg-white"
                                                   placeholder="0.00">
                                            <span class="text-[10px] font-black text-gray-400 tracking-widest text-primary">{{ __('messages.currency_sar') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-end">
                                        <button type="button" @click="removeEmployee(index)" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <div x-show="assignedEmployees.length === 0" class="py-12 text-center bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-100">
                        <div class="flex flex-col items-center justify-center grayscale opacity-50">
                            <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            <span class="text-sm font-bold text-gray-500">{{ __('messages.no_employees_assigned') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('client.medical-insurance.policies.index', ['client_slug' => request('client_slug')]) }}" class="px-10 py-5 bg-gray-50 hover:bg-gray-100 text-secondary font-black rounded-[2rem] transition-all">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="px-12 py-5 bg-primary hover:bg-[#8affaa] text-secondary font-black rounded-[2rem] shadow-xl shadow-primary/20 transition-all transform hover:-translate-y-1 active:translate-y-0">
                    {{ __('messages.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
