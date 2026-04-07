@extends('layouts.employee')

@section('content')
<div class="space-y-10">
<div class="pt-8 pb-12">
    <!-- Standard Header -->
    <x-dashboard-sub-header 
        :title="__('messages.my_tasks')" 
        :subtitle="__('messages.my_tasks_desc')"
    />


    <!-- Tasks Table Container -->
    <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('messages.task') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('messages.due_date') }}
                        </th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            {{ __('messages.status') }}
                        </th>
                        <th class="px-10 py-7 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                            {{ __('messages.actions') }}
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
                                        <div class="flex items-center gap-2">
                                            <div class="text-base font-black text-secondary tracking-tight group-hover/row:text-blue-600 transition-colors uppercase">{{ $task->title }}</div>
                                            @if($task->attachments && count($task->attachments) > 0)
                                                <div class="relative group/att">
                                                    <div class="w-5 h-5 rounded-lg bg-blue-100 flex items-center justify-center" title="{{ count($task->attachments) }} {{ __('messages.attachments') }}">
                                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                    </div>
                                                    @if(count($task->attachments) > 1)
                                                        <span class="absolute -top-1.5 -right-1.5 bg-blue-600 text-white text-[7px] font-black w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">{{ count($task->attachments) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-400 font-medium leading-relaxed mt-1 max-w-xl">{{ $task->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">{{ __('messages.deadline') }}</span>
                                    <div class="text-sm {{ $task->due_date?->isPast() && $task->status != 'done' ? 'text-rose-500 font-black' : 'text-secondary font-black' }}">
                                        {{ $task->due_date?->translatedFormat('d M, Y') ?: '—' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-center">
                                <div class="relative inline-block w-40 group/select mx-auto">
                                    <form action="{{ route('employee.tasks.updateStatus', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="w-full px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all duration-300 appearance-none cursor-pointer focus:ring-0 focus:outline-none shadow-sm {{ 
                                                $task->status == 'todo' ? 'bg-gray-100 text-gray-500 border-gray-200' : 
                                                ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-600 border-blue-200' : 
                                                'bg-emerald-100 text-emerald-600 border-emerald-200')
                                            }} group-hover/select:shadow-md">
                                            <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>{{ __('messages.todo') }}</option>
                                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>{{ __('messages.in_progress') }}</option>
                                            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>{{ __('messages.done') }}</option>
                                        </select>
                                        <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-3' : 'right-3' }} flex items-center pointer-events-none text-gray-400 group-hover/select:text-secondary transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </form>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-right">
                                @if($task->attachments && count($task->attachments) > 0)
                                    @php
                                        $attachment = $task->attachments[0];
                                        $path = is_array($attachment) ? ($attachment['path'] ?? $attachment) : $attachment;
                                    @endphp
                                    <a href="{{ Storage::url($path) }}" target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-500 hover:text-white text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        {{ count($task->attachments) > 1 ? __('messages.view_all') : __('messages.download') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-2-12a2 2 0 002 2h2a2 2 0 002-2" /></svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_tasks_found') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_tasks_desc') }}</p>
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

