@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="w-full">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-xl rounded-[2.5rem] p-10 text-white mb-10 relative group">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black mb-2 tracking-tight text-white">{{ __('messages.attendance') ?? __('Attendance') }}</h1>
                    <p class="text-blue-100 text-lg opacity-90">{{ __('messages.attendance_desc') ?? 'Track and manage your team\'s daily attendance with professional precision.' }}</p>
                </div>
                
                <!-- Date Filter in Hero -->
                <div class="flex items-center gap-4">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-3xl flex flex-col md:flex-row items-center gap-4 group/date transition-all hover:bg-white/15">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-white group-hover/date:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-blue-200 mb-0.5">{{ __('messages.select_date') ?? __('Select Date') }}</p>
                                <p class="text-sm font-black text-white whitespace-nowrap">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <form method="GET" action="{{ route('client.attendance.index') }}">
                            <input type="date" name="date" value="{{ $date }}" 
                                   max="{{ now()->format('Y-m-d') }}"
                                   onchange="this.form.submit()"
                                   class="bg-white/10 border-2 border-white/20 focus:border-white focus:bg-white/20 rounded-2xl px-5 py-2.5 text-sm font-bold text-white outline-none cursor-pointer transition-all">
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-3rem] right-[-3rem] w-64 h-64 bg-white opacity-5 rounded-full transition-transform duration-1000 group-hover:scale-125"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-32 h-32 bg-indigo-400 opacity-10 rounded-full transition-transform duration-700 group-hover:-translate-y-8"></div>
        </div>

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
                                <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee_name') ?? __('Employee') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.present') ?? __('Present') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.late') ?? __('Late') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.absent') ?? __('Absent') }}</th>
                                <th class="px-4 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.leave') ?? __('Leave') }}</th>
                                <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.notes') ?? __('Notes') }}</th>

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
                                                $displayName = app()->getLocale() == 'ar' ? $employee->name_ar : ($employee->name_en ?? $employee->name_ar);
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
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-emerald-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.present') ?? __('Present') }}</span>
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
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-amber-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.late') ?? __('Late') }}</span>
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
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-rose-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.absent') ?? __('Absent') }}</span>
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
                                            <span class="mt-2 text-[8px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.leave') ?? __('Leave') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-10 py-7 whitespace-nowrap">
                                        <div class="relative">
                                            <input type="text" name="attendance[{{ $employee->id }}][notes]" 
                                                value="{{ old("attendance.{$employee->id}.notes", $record?->notes) }}"
                                                placeholder="{{ __('messages.add_note_placeholder') ?? __('Add note...') }}"
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
                                            <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_employees_attendance') ?? __('No employees found.') }}</h3>
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
