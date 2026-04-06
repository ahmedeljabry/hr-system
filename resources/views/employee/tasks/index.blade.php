@extends('layouts.employee')

@section('content')
<div class="space-y-10">
    <!-- Premium Hero Section -->
    <div class="relative bg-secondary overflow-hidden shadow-2xl rounded-[2.5rem] p-12 text-white mb-10 transition-all duration-700 group hover:shadow-secondary/20">
        <div class="relative z-10 text-center md:text-left {{ app()->getLocale() == 'ar' ? 'md:text-right' : '' }}">
            <h1 class="text-4xl font-black mb-2 tracking-tight">{{ __('My Tasks') }}</h1>
            <p class="text-primary text-lg opacity-90 font-medium">{{ __('View and track tasks assigned to you by your organization.') }}</p>
        </div>
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 text-white opacity-10 group-hover:scale-110 transition-transform duration-1000">
            <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
        </div>
    </div>

    <!-- Tasks Table Container -->
    <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Task') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Due Date') }}
                        </th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            {{ __('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-blue-50/20 transition-all duration-300 group/row">
                            <td class="px-10 py-7">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover/row:scale-110 transition-transform mt-1 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-base font-black text-secondary tracking-tight group-hover/row:text-blue-600 transition-colors uppercase">{{ $task->title }}</div>
                                        <div class="text-sm text-gray-400 font-medium leading-relaxed mt-1 max-w-xl">{{ $task->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">{{ __('Deadline') }}</span>
                                    <div class="text-sm {{ $task->due_date?->isPast() && $task->status != 'done' ? 'text-rose-500 font-black' : 'text-secondary font-black' }}">
                                        {{ $task->due_date?->translatedFormat('d M, Y') ?: '—' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-center">
                                @php
                                    $statusConfig = [
                                        'todo' => 'bg-gray-100 text-gray-500 border-gray-200',
                                        'in_progress' => 'bg-blue-100 text-blue-600 border-blue-200',
                                        'done' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                    ][$task->status] ?? 'bg-gray-100 text-gray-500 border-gray-200';
                                @endphp
                                <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusConfig }}">
                                    {{ __($task->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-2-12a2 2 0 002 2h2a2 2 0 002-2" /></svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('No tasks assigned.') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('You have completed all your tasks or haven\'t been assigned any yet.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

