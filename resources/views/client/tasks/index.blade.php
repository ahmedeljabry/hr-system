@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.tasks')" 
            :subtitle="__('messages.tasks_desc')"
        >
            <x-slot name="actions">
                <a href="{{ route('client.tasks.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/add">
                    <svg class="w-4 h-4 me-2 group-hover/add:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('messages.add_task') }}
                </a>
            </x-slot>
        </x-dashboard-sub-header>


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

        <!-- Table Container -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.task_title') }}</th>
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.assigned_to') }}</th>
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.due_date') }}</th>
                            <th class="px-8 py-6 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                            <th class="px-8 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-secondary/5 flex items-center justify-center text-secondary font-black">
                                            {{ substr($task->title, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <div class="text-base font-black text-secondary tracking-tight">{{ $task->title }}</div>
                                                @if($task->attachments && count($task->attachments) > 0)
                                                    <div class="relative group/att">
                                                        <div class="w-5 h-5 rounded-lg bg-primary/20 flex items-center justify-center" title="{{ count($task->attachments) }} {{ __('attachments') }}">
                                                            <svg class="w-3 h-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                        </div>
                                                        @if(count($task->attachments) > 1)
                                                            <span class="absolute -top-1.5 -right-1.5 bg-secondary text-primary text-[7px] font-black w-3.5 h-3.5 rounded-full flex items-center justify-center border border-white">{{ count($task->attachments) }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest truncate max-w-xs">{{ $task->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @php
                                            $displayName = $task->employee ? $task->employee->name : __('messages.unassigned');
                                        @endphp
                                        <x-avatar :name="$displayName ?? '?'" size="xs" class="rounded-lg shadow-sm border border-gray-100" />
                                        <span class="text-sm font-bold text-gray-600">{{ $displayName ?: __('messages.unassigned') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black {{ $task->due_date?->isPast() && $task->status != 'done' ? 'text-red-500' : 'text-secondary/70' }}">
                                            {{ $task->due_date?->format('M d, Y') ?: '-' }}
                                        </span>
                                        @if($task->due_date?->isPast() && $task->status != 'done')
                                            <span class="text-[9px] font-black uppercase text-red-400 tracking-tighter">{{ __('messages.overdue') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-center">
                                    @php
                                        $statusConfig = [
                                            'todo' => 'bg-gray-100 text-gray-500',
                                            'in_progress' => 'bg-secondary text-white',
                                            'done' => 'bg-primary/20 text-secondary border border-primary/30',
                                        ][$task->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $statusConfig }}">
                                        {{ __('messages.' . $task->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-3">
                                        @if($task->attachments && count($task->attachments) > 0)
                                            @php
                                                $attachment = $task->attachments[0];
                                                $path = is_array($attachment) ? ($attachment['path'] ?? $attachment) : $attachment;
                                                $fileName = is_array($attachment) ? ($attachment['name'] ?? null) : null;
                                            @endphp
                                            <a href="{{ route('client.files.task.attachment', ['client_slug' => request()->route('client_slug'), 'task' => $task->id, 'index' => 0]) }}" 
                                               @if($fileName) download="{{ $fileName }}" @else target="_blank" @endif
                                               class="w-10 h-10 rounded-2xl bg-primary/10 text-primary-dark hover:bg-primary transition-all duration-300 flex items-center justify-center hover:scale-110 active:scale-95 group/file shadow-sm"
                                               title="{{ count($task->attachments) > 1 ? __('messages.view_all_attachments') ?? __('View all attachments') : __('messages.view_attachment') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('client.tasks.edit', $task) }}" 
                                           class="w-10 h-10 rounded-2xl bg-secondary/5 text-secondary hover:bg-secondary hover:text-white transition-all duration-300 flex items-center justify-center hover:scale-110 active:scale-95 group/edit shadow-sm">
                                            <svg class="w-5 h-5 group-hover/edit:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form id="delete-task-{{ $task->id }}" action="{{ route('client.tasks.destroy', $task) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" 
                                                onclick="Swal.fire({
                                                    title: '{{ __('messages.are_you_sure') }}',
                                                    text: '{{ __('messages.confirm_delete') }}',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#ef4444',
                                                    cancelButtonColor: '#0ea5e9',
                                                    confirmButtonText: '{{ __('messages.yes_delete') }}',
                                                    cancelButtonText: '{{ __('messages.cancel') }}',
                                                    reverseButtons: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('delete-task-{{ $task->id }}').submit();
                                                    }
                                                })"
                                                class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-300 flex items-center justify-center hover:scale-110 active:scale-95 group/delete shadow-sm">
                                            <svg class="w-5 h-5 group-hover/delete:shake transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <p class="text-xl font-black text-secondary/30">{{ __('messages.no_tasks_found') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tasks->hasPages())
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
