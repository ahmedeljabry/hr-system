@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.attendance')" 
            :subtitle="__('messages.attendance_desc')"
        >
            <x-slot name="actions">
                <div class="flex items-center gap-4 bg-white/5 backdrop-blur-md border border-white/10 p-2 rounded-2xl">
                    <form method="GET" action="{{ route('client.attendance.index') }}" class="flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-primary/80 ps-2">{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</span>
                        <input type="date" name="date" value="{{ $date }}" 
                               max="{{ now()->format('Y-m-d') }}"
                               onchange="this.form.submit()"
                               class="bg-white/10 border-0 focus:ring-2 focus:ring-primary/50 rounded-xl px-3 py-1.5 text-xs font-bold text-white outline-none cursor-pointer transition-all">
                    </form>
                </div>
            </x-slot>
        </x-dashboard-sub-header>


        @if(session('success'))
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('client.attendance.store') }}" method="POST"
              x-data="{ 
                saving: false,
                lastSaved: null,
                async saveAttendance(employeeId, status, notes = null) {
                    this.saving = true;
                    this.lastSaved = employeeId;
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('date', '{{ $date }}');
                    formData.append(`attendance[${employeeId}][status]`, status || document.querySelector(`input[name='attendance[${employeeId}][status]']:checked`)?.value);
                    if (notes !== null) {
                        formData.append(`attendance[${employeeId}][notes]`, notes);
                    } else {
                        const noteInput = document.querySelector(`input[name='attendance[${employeeId}][notes]']`);
                        if (noteInput) formData.append(`attendance[${employeeId}][notes]`, noteInput.value);
                    }
                    
                    try {
                        const response = await fetch('{{ route('client.attendance.store') }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await response.json();
                        if (data.success) {
                            console.log('Saved:', employeeId);
                        }
                    } catch (e) {
                        console.error('Save failed:', e);
                    } finally {
                        setTimeout(() => { if (this.lastSaved === employeeId) this.saving = false; }, 1000);
                    }
                }
              }">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <!-- Table Container -->
            <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee_name') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.present') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.late') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.absent') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.leave') }}</th>
                                <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.notes') }}</th>

                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($employees as $employee)
                                @php 
                                    $record = $records->get($employee->id);
                                    $currentStatus = old("attendance.{$employee->id}.status", $record?->status);
                                @endphp
                                <tr class="hover:bg-blue-50/20 transition-all duration-300 group/row">
                                    <td class="px-10 py-7 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            @php
                                                $displayName = $employee->name;
                                            @endphp
                                            <a href="{{ route('client.employees.show', $employee) }}" class="flex items-center gap-4 group/profile">
                                                <div class="relative">
                                                    <x-avatar :name="$displayName" size="md" class="rounded-2xl shadow-sm border border-gray-100 group-hover/profile:border-primary transition-all shadow-sm" />
                                                    <template x-if="saving && lastSaved == {{ $employee->id }}">
                                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                                                            <div class="w-2 h-2 bg-primary rounded-full animate-ping"></div>
                                                        </div>
                                                    </template>
                                                </div>
                                                <div>
                                                    <div class="text-base font-black text-secondary tracking-tight capitalize group-hover/profile:text-primary transition-colors">{{ $displayName }}</div>
                                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $employee->position }}</div>
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-4 py-7 text-center">
                                        <label class="inline-flex flex-col items-center cursor-pointer group/opt">
                                            <input type="radio" name="attendance[{{ $employee->id }}][status]" value="present" 
                                                {{ $currentStatus == 'present' ? 'checked' : '' }}
                                                @change="saveAttendance({{ $employee->id }}, 'present')"
                                                class="peer hidden">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border-2 border-transparent peer-checked:bg-emerald-500 peer-checked:border-emerald-200 flex items-center justify-center text-gray-300 peer-checked:text-white transition-all duration-300 group-hover/opt:scale-110 shadow-sm group-hover/opt:shadow-emerald-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-emerald-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.present') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-7 text-center">
                                        <label class="inline-flex flex-col items-center cursor-pointer group/opt">
                                            <input type="radio" name="attendance[{{ $employee->id }}][status]" value="late" 
                                                {{ $currentStatus == 'late' ? 'checked' : '' }}
                                                @change="saveAttendance({{ $employee->id }}, 'late')"
                                                class="peer hidden">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border-2 border-transparent peer-checked:bg-amber-500 peer-checked:border-amber-200 flex items-center justify-center text-gray-300 peer-checked:text-white transition-all duration-300 group-hover/opt:scale-110 shadow-sm group-hover/opt:shadow-amber-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-amber-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.late') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-7 text-center">
                                        <label class="inline-flex flex-col items-center cursor-pointer group/opt">
                                            <input type="radio" name="attendance[{{ $employee->id }}][status]" value="absent" 
                                                {{ $currentStatus == 'absent' ? 'checked' : '' }}
                                                @change="saveAttendance({{ $employee->id }}, 'absent')"
                                                class="peer hidden">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border-2 border-transparent peer-checked:bg-rose-500 peer-checked:border-rose-200 flex items-center justify-center text-gray-300 peer-checked:text-white transition-all duration-300 group-hover/opt:scale-110 shadow-sm group-hover/opt:shadow-rose-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-rose-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.absent') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-7 text-center">
                                        <label class="inline-flex flex-col items-center cursor-pointer group/opt">
                                            <input type="radio" name="attendance[{{ $employee->id }}][status]" value="leave" 
                                                {{ $currentStatus == 'leave' ? 'checked' : '' }}
                                                @change="saveAttendance({{ $employee->id }}, 'leave')"
                                                class="peer hidden">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border-2 border-transparent peer-checked:bg-blue-500 peer-checked:border-blue-200 flex items-center justify-center text-gray-300 peer-checked:text-white transition-all duration-300 group-hover/opt:scale-110 shadow-sm group-hover/opt:shadow-blue-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.leave') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-10 py-7 whitespace-nowrap">
                                        <div class="relative">
                                            <input type="text" name="attendance[{{ $employee->id }}][notes]" 
                                                value="{{ old("attendance.{$employee->id}.notes", $record?->notes) }}"
                                                placeholder="{{ __('messages.add_note_placeholder') }}"
                                                @change="saveAttendance({{ $employee->id }}, null, $el.value)"
                                                class="block w-full bg-gray-50/50 border-2 border-transparent focus:border-blue-500/20 focus:bg-white rounded-2xl py-3 px-5 text-xs font-bold text-secondary transition-all duration-300 outline-none shadow-sm">
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-10 py-32 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            </div>
                                            <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_employees_attendance') }}</h3>
                                            <a href="{{ route('client.employees.create') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-black text-sm transition-colors mt-4">
                                                {{ __('messages.add_employee') }}
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->isNotEmpty())
                    <div class="bg-gray-50/50 px-10 py-12 border-t border-gray-100 flex justify-center">
                        <button type="submit" class="inline-flex items-center px-16 py-5 bg-primary text-secondary hover:bg-primary/90 text-lg font-black rounded-3xl shadow-2xl shadow-primary/20 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 group/submit">
                            <svg class="w-7 h-7 me-4 group-hover/submit:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('messages.save_attendance') ?? __('Save Records') }}
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium custom styling */
    input[type="radio"]:checked + div {
        transform: scale(1.15);
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
