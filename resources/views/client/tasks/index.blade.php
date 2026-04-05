@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.tasks') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('messages.tasks_desc') }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                        {{ __('messages.add_task') }}
                    </a>
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
                                            <div class="text-base font-black text-secondary tracking-tight">{{ $task->title }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest truncate max-w-xs">{{ $task->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center text-[10px] font-black text-secondary">
                                            {{ substr($task->employee?->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-gray-600">{{ $task->employee?->name ?: __('messages.unassigned') }}</span>
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
                                    <div class="flex items-center justify-end gap-3 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}">
                                        <a href="{{ route('client.tasks.edit', $task) }}" 
                                           class="p-2.5 text-gray-400 hover:text-secondary hover:bg-gray-100 rounded-xl transition-all duration-300 group/edit">
                                            <svg class="h-5 w-5 group-hover/edit:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('client.tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.are_you_sure') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-300 group/del">
                                                <svg class="h-5 w-5 group-hover/del:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_tasks') }}</h3>
                                        <p class="text-sm text-gray-400 max-w-xs mx-auto mb-6">{{ __('messages.tasks_empty_desc') }}</p>
                                        <a href="{{ route('client.tasks.create') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-black text-sm transition-colors">
                                            {{ __('messages.add_task') }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                        </a>
                                    </div>
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
