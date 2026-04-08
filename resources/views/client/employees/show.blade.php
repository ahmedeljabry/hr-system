@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full mx-auto">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header :title="$employee->name">
            <x-slot:leading>
                <div class="relative group/avatar">
                    <x-avatar :name="$employee->name" size="2xl" class="shadow-2xl border-4 border-white/10 group-hover/avatar:border-primary/50 transition-all duration-500" />
                    <div class="absolute inset-0 rounded-full bg-primary/20 opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-500"></div>
                </div>
            </x-slot:leading>

            <x-slot:subtitle>
                <div class="flex flex-wrap items-center gap-y-2 gap-x-6 mt-2">
                    <span class="flex items-center gap-2.5 text-gray-400 group/item">
                        <span class="p-1.5 bg-white/5 rounded-lg border border-white/5 group-hover/item:border-primary/30 transition-colors">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </span>
                        <span class="text-sm font-bold tracking-wide">{{ $employee->position }}</span>
                    </span>

                    <span class="hidden md:block w-px h-4 bg-white/10"></span>

                    @if($employee->status === 'terminated' && $employee->termination)
                        <span class="flex items-center gap-2.5 text-gray-400 group/item">
                            <span class="p-1.5 bg-red-400/10 rounded-lg border border-red-400/20 group-hover/item:border-red-400/40 transition-colors">
                                <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v14a2 2 0 002 2z" /></svg>
                            </span>
                            <span class="text-sm font-bold tracking-wide">{{ __('messages.terminated_at') }}: {{ $employee->termination->terminated_at->format('d/m/Y') }}</span>
                        </span>
                    @else
                        <span class="flex items-center gap-2.5 text-gray-400 group/item">
                            <span class="p-1.5 bg-white/5 rounded-lg border border-white/5 group-hover/item:border-primary/30 transition-colors">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v14a2 2 0 002 2z" /></svg>
                            </span>
                            <span class="text-sm font-bold tracking-wide">{{ __('messages.hire_date') }}: {{ $employee->hire_date->format('d/m/Y') }}</span>
                        </span>
                    @endif
                </div>
            </x-slot:subtitle>

            <x-slot:actions>
                <div class="flex items-center gap-3">
                    @if($employee->status === 'active')
                        <a href="{{ route('client.employees.terminate.form', $employee) }}" 
                           class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 border border-red-400 text-white text-xs font-black rounded-2xl transition-all duration-300 shadow-lg">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            {{ __('messages.terminate') }}
                        </a>
                    @endif

                    @if($employee->status === 'active')
                        <a href="{{ route('client.employees.edit', $employee) }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-black rounded-2xl transition-all duration-300 backdrop-blur-md">
                            <svg class="w-4 h-4 me-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('messages.edit') }}
                        </a>
                    @endif

                    
                    <a href="{{ route('client.employees.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-black rounded-2xl transition-all duration-300 backdrop-blur-md group/back">
                        <svg class="w-4 h-4 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                </div>
            </x-slot:actions>
        </x-dashboard-sub-header>

        @if($employee->status === 'terminated' && $employee->termination)
            <div class="mb-8 bg-red-50 border-l-4 border-red-400 p-8 rounded-3xl shadow-sm">
                <div class="flex items-center gap-6">
                    <div class="bg-red-100 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-red-900 mb-1">{{ __('messages.employee_terminated_view') }}</h3>
                        <p class="text-sm font-bold text-red-700 opacity-80">
                            {{ \App\Enums\TerminationReason::from((int) $employee->termination->reason_case)->label() }} 
                            • {{ __('messages.terminated_at') }}: {{ $employee->termination->terminated_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                @if($employee->termination->comments)
                    <div class="mt-6 p-6 bg-white/50 rounded-2xl border border-red-100 text-sm font-bold text-red-800 italic">
                        "{{ $employee->termination->comments }}"
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ activeTab: 'info' }">
            <!-- Left Column -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Navigation Tabs -->
                <div class="flex items-center gap-2 p-1.5 bg-gray-100/50 rounded-2xl border border-gray-200/50 w-fit">
                    <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'bg-white text-secondary shadow-sm' : 'text-gray-400 hover:text-secondary'" class="px-6 py-2.5 rounded-xl text-xs font-black transition-all">
                        {{ __('messages.personal_information') }}
                    </button>
                    <button @click="activeTab = 'leaves'" :class="activeTab === 'leaves' ? 'bg-white text-secondary shadow-sm' : 'text-gray-400 hover:text-secondary'" class="px-6 py-2.5 rounded-xl text-xs font-black transition-all">
                        {{ __('messages.leave_history') }}
                    </button>
                    <button @click="activeTab = 'tasks'" :class="activeTab === 'tasks' ? 'bg-white text-secondary shadow-sm' : 'text-gray-400 hover:text-secondary'" class="px-6 py-2.5 rounded-xl text-xs font-black transition-all">
                        {{ __('messages.task_history') }}
                    </button>
                </div>

                <div x-show="activeTab === 'info'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <!-- Financial Summary Card -->
                    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
                        <div class="p-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-1c.51-.418.815-1.002.815-1.599 0-1.105-1.343-2-3-2s-3 .895-3 2 1.343 2 3 2m.599 1.599c-.51.418-.815 1.002-.815 1.599 0 1.105 1.343 2 3 2s3-.895 3-2-1.343-2-3-2m-3.599 1.599c.51.418.815 1.002.815 1.599"></path></svg>
                                </div>
                                <h2 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.total_salary') }}</h2>
                                <div class="ms-auto flex items-baseline gap-1">
                                    <span class="text-4xl font-black text-primary">{{ number_format($employee->total_salary, 2) }}</span>
                                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ __('messages.currency_sar') }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.basic_salary') }}</span>
                                    <span class="text-lg font-black text-secondary">{{ number_format($employee->basic_salary, 2) }}</span>
                                </div>
                                <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.housing_allowance') }}</span>
                                    <span class="text-lg font-black text-secondary">{{ number_format($employee->housing_allowance, 2) }}</span>
                                </div>
                                <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.transportation_allowance') }}</span>
                                    <span class="text-lg font-black text-secondary">{{ number_format($employee->transportation_allowance, 2) }}</span>
                                </div>
                                <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-between items-center transition-all hover:border-primary/20">
                                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('messages.other_allowances') }}</span>
                                    <span class="text-lg font-black text-secondary">{{ number_format($employee->other_allowances, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal & Contact Information -->
                    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
                        <div class="p-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 rounded-2xl bg-secondary/10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <h2 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.personal_information') }}</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-8">
                                <div class="space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.national_id_number') }}</dt>
                                    <dd class="text-lg font-black text-secondary font-mono">{{ $employee->national_id_number }}</dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.nationality') }}</dt>
                                    <dd class="text-lg font-black text-secondary">{{ $employee->nationality_label }}</dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.gender') }}</dt>
                                    <dd class="text-lg font-black text-secondary capitalize">{{ $employee->gender ?: '—' }}</dd>
                                </div>

                                @if($employee->nationality && strtolower($employee->nationality) !== 'saudi' && $employee->residency_number)
                                    <div class="space-y-1">
                                        <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.residency_number') }}</dt>
                                        <dd class="text-lg font-black text-secondary font-mono">{{ $employee->residency_number }}</dd>
                                    </div>
                                    <div class="space-y-1">
                                        <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.residency_end_date') }}</dt>
                                        <dd class="text-lg font-black text-secondary">{{ $employee->residency_end_date ? $employee->residency_end_date->format('d/m/Y') : '—' }}</dd>
                                    </div>
                                @endif


                                <div class="space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.date_of_birth') }}</dt>
                                    <dd class="text-lg font-black text-secondary">{{ $employee->date_of_birth ? $employee->date_of_birth->format('d/m/Y') : '—' }}</dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.bank_iban') }}</dt>
                                    <dd class="text-lg font-black text-secondary font-mono">{{ $employee->bank_iban ?: '—' }}</dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.phone') }}</dt>
                                    <dd class="text-lg font-black text-secondary">{{ $employee->phone ?: '—' }}</dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.emergency_phone') }}</dt>
                                    <dd class="text-lg font-black text-secondary">{{ $employee->emergency_phone ?: '—' }}</dd>
                                </div>
                                <div class="md:col-span-2 space-y-1">
                                    <dt class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('messages.email') }}</dt>
                                    <dd class="text-lg font-black text-secondary">{{ $employee->email ?: ($employee->user?->email ?: '—') }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leaves Tab -->
                <div x-show="activeTab === 'leaves'" class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="p-10">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-orange-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <h2 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.leave_history') }}</h2>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @forelse($employee->leaveRequests()->latest()->get() as $leave)
                                <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex items-center justify-between group hover:border-primary/20 transition-all">
                                    <div>
                                        <div class="text-sm font-black text-secondary mb-1">{{ $leave->leaveType->name }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $leave->start_date->format('d/m/Y') }} — {{ $leave->end_date->format('d/m/Y') }} ({{ $leave->total_days }} {{ __('messages.days') }})</div>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ 
                                        $leave->status === 'approved' ? 'bg-green-100 text-green-600' : 
                                        ($leave->status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600') 
                                    }}">
                                        {{ __('messages.' . $leave->status) }}
                                    </span>
                                </div>
                            @empty
                                <div class="py-12 text-center opacity-40">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                                    <p class="font-bold text-gray-500">{{ __('messages.no_leave_history') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tasks Tab -->
                <div x-show="activeTab === 'tasks'" class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="p-10">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </div>
                                <h2 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.task_history') }}</h2>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @forelse($employee->tasks()->latest()->get() as $task)
                                <div class="p-6 rounded-3xl bg-gray-50 border border-gray-100 flex items-center justify-between group hover:border-primary/20 transition-all">
                                    <div>
                                        <div class="text-sm font-black text-secondary mb-1">{{ $task->title }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('messages.due_date') }}: {{ $task->due_date ? $task->due_date->format('d/m/Y') : '—' }}</div>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ 
                                        $task->status === 'completed' ? 'bg-green-100 text-green-600' : 
                                        ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-600') 
                                    }}">
                                        {{ __('messages.' . $task->status) }}
                                    </span>
                                </div>
                            @empty
                                <div class="py-12 text-center opacity-40">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    <p class="font-bold text-gray-500">{{ __('messages.no_task_history') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Documents -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden sticky top-8">
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h2 class="text-xl font-black text-secondary tracking-tight">{{ __('messages.documents') }}</h2>
                        </div>

                        <div class="space-y-4">
                            <!-- ID Copy -->
                            @include('client.employees._doc-action', [
                                'label' => __('messages.national_id_image'),
                                'file' => $employee->national_id_image,
                                'type' => 'national_id'
                            ])

                            <!-- CV -->
                            @include('client.employees._doc-action', [
                                'label' => __('messages.cv_file'),
                                'file' => $employee->cv_file,
                                'type' => 'cv'
                            ])

                            <!-- Contract -->
                            @include('client.employees._doc-action', [
                                'label' => __('messages.contract_image'),
                                'file' => $employee->contract_image,
                                'type' => 'contract'
                            ])

                            <!-- Other Documents -->
                            @if(!empty($employee->other_documents))
                                <div class="pt-4 border-t border-gray-50">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 italic">{{ __('messages.other_documents') }}</p>
                                    <div class="space-y-3">
                                        @foreach($employee->other_documents as $index => $path)
                                            <a href="{{ route('client.files.employee', [$employee->id, 'other', 'index' => $index]) }}" 
                                               class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 hover:bg-primary/10 border border-transparent transition-all group/doc">
                                                <div class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center group-hover/doc:text-primary transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <span class="text-xs font-bold text-secondary truncate">{{ basename($path) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
