@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.attendance') ?? __('Attendance') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('messages.attendance_desc') ?? 'Track and manage your team\'s daily attendance with professional precision.' }}</p>
                </div>
                
                <!-- Date Filter in Hero -->
                <div class="flex items-center gap-4">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex flex-col md:flex-row items-center gap-4 group/date">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary group-hover/date:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-0.5">{{ __('messages.select_date') ?? __('Select Date') }}</p>
                                <p class="text-sm font-black text-white whitespace-nowrap">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <form method="GET" action="{{ route('client.attendance.index') }}">
                            <input type="date" name="date" value="{{ $date }}" 
                                   max="{{ now()->format('Y-m-d') }}"
                                   onchange="this.form.submit()"
                                   class="bg-white/5 border-2 border-white/10 focus:border-primary rounded-xl px-4 py-2 text-sm font-bold text-white outline-none cursor-pointer transition-all">
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        @if(session('success'))
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('client.attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <!-- Table Container -->
            <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee_name') ?? __('Employee') }}</th>
                                <th class="px-4 py-6 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.present') ?? __('Present') }}</th>
                                <th class="px-4 py-6 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.late') ?? __('Late') }}</th>
                                <th class="px-4 py-6 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.absent') ?? __('Absent') }}</th>
                                <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.notes') ?? __('Notes') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($employees as $employee)
                                @php 
                                    $record = $records->get($employee->id);
                                    $currentStatus = old("attendance.{$employee->id}.status", $record?->status ?: 'present');
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-all duration-300">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-2xl bg-secondary/5 flex items-center justify-center text-secondary font-black">
                                                {{ substr($employee->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-base font-black text-secondary tracking-tight">{{ $employee->name }}</div>
                                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $employee->position }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-6 text-center">
                                        <label class="inline-flex flex-col items-center cursor-pointer group">
                                            <input type="radio" name="attendance[{{ $employee->id }}][status]" value="present" 
                                                {{ $currentStatus == 'present' ? 'checked' : '' }}
                                                class="peer hidden">
                                            <div class="w-10 h-10 rounded-2xl bg-gray-50 border-2 border-transparent peer-checked:bg-emerald-500 peer-checked:border-emerald-200 flex items-center justify-center text-gray-300 peer-checked:text-white transition-all duration-300 group-hover:scale-110 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="mt-2 text-[9px] font-bold uppercase tracking-widest text-gray-400 peer-checked:text-emerald-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.present') ?? __('Present') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-6 text-center">
                                        <label class="inline-flex flex-col items-center cursor-pointer group">
                                            <input type="radio" name="attendance[{{ $employee->id }}][status]" value="late" 
                                                {{ $currentStatus == 'late' ? 'checked' : '' }}
                                                class="peer hidden">
                                            <div class="w-10 h-10 rounded-2xl bg-gray-50 border-2 border-transparent peer-checked:bg-amber-500 peer-checked:border-amber-200 flex items-center justify-center text-gray-300 peer-checked:text-white transition-all duration-300 group-hover:scale-110 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <span class="mt-2 text-[9px] font-bold uppercase tracking-widest text-gray-400 peer-checked:text-amber-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.late') ?? __('Late') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-6 text-center">
                                        <label class="inline-flex flex-col items-center cursor-pointer group">
                                            <input type="radio" name="attendance[{{ $employee->id }}][status]" value="absent" 
                                                {{ $currentStatus == 'absent' ? 'checked' : '' }}
                                                class="peer hidden">
                                            <div class="w-10 h-10 rounded-2xl bg-gray-50 border-2 border-transparent peer-checked:bg-red-500 peer-checked:border-red-200 flex items-center justify-center text-gray-300 peer-checked:text-white transition-all duration-300 group-hover:scale-110 shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                            <span class="mt-2 text-[9px] font-bold uppercase tracking-widest text-gray-400 peer-checked:text-red-600 opacity-0 peer-checked:opacity-100 transition-all">{{ __('messages.absent') ?? __('Absent') }}</span>
                                        </label>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="relative group/note">
                                            <input type="text" name="attendance[{{ $employee->id }}][notes]" 
                                                value="{{ old("attendance.{$employee->id}.notes", $record?->notes) }}"
                                                placeholder="{{ __('messages.add_note_placeholder') ?? __('Add optional note...') }}"
                                                class="block w-full bg-gray-50 border-2 border-transparent focus:border-primary/30 focus:bg-white rounded-xl py-2.5 px-4 text-xs font-bold text-secondary transition-all duration-300 outline-none">
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            </div>
                                            <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_employees_attendance') ?? __('No employees found.') }}</h3>
                                            <a href="{{ route('client.employees.create') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-black text-sm transition-colors">
                                                {{ __('messages.add_employee') }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->isNotEmpty())
                    <div class="bg-gray-50/50 px-8 py-10 border-t border-gray-100 flex justify-center">
                        <button type="submit" class="inline-flex items-center px-20 py-5 bg-primary hover:bg-[#8affaa] text-secondary text-lg font-black rounded-3xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/submit">
                            <svg class="w-7 h-7 me-4 group-hover/submit:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('messages.save_attendance') ?? __('Save Records') }}
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom radio styling for better UX */
    input[type="radio"]:checked + div {
        transform: scale(1.1);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
