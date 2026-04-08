@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        
        <!-- Premium Header -->
        <x-dashboard-sub-header 
            :title="__('messages.action_required') ?? 'Management Center'" 
            :subtitle="__('messages.action_required_desc') ?? 'Identify and manage items that require administrative attention.'"
        />

        @if(session('success'))
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-12">
            
            <!-- 1. Rejected Leave Requests Section -->
            <section class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-2 h-8 bg-rose-500 rounded-full"></div>
                    <h3 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.rejected_leaves') ?? 'Rejected Leave Requests' }}</h3>
                    <span class="bg-rose-50 text-rose-600 px-4 py-1.5 rounded-full text-xs font-black border border-rose-100">{{ $rejectedLeaves->count() }}</span>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee') }}</th>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.leave_type') }}</th>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.period') }}</th>
                                    <th class="px-10 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($rejectedLeaves as $leave)
                                    <tr class="hover:bg-rose-50/30 transition-all duration-300">
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <x-avatar :name="$leave->employee->name" size="md" class="rounded-2xl" />
                                                <span class="text-base font-black text-secondary">{{ $leave->employee->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <span class="text-sm font-bold text-gray-600">{{ $leave->leaveType->name }}</span>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <div class="text-sm font-black text-secondary opacity-70">{{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                            <div class="flex items-center justify-end gap-3">
                                                <form action="{{ route('client.action-required.leaves.destroy', $leave) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-3 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-2xl transition-all duration-300 group/del" title="{{ __('messages.delete') }}">
                                                        <svg class="w-5 h-5 group-hover/del:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-16 text-center text-gray-300 font-bold italic">{{ __('messages.no_rejected_leaves') ?? 'No rejected leave requests found.' }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- 2. Overdue Tasks Section -->
            <section class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-2 h-8 bg-amber-500 rounded-full"></div>
                    <h3 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.overdue_tasks') ?? 'Overdue Tasks' }}</h3>
                    <span class="bg-amber-50 text-amber-600 px-4 py-1.5 rounded-full text-xs font-black border border-amber-100">{{ $overdueTasks->count() }}</span>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.task_title') }}</th>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.assigned_to') }}</th>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.due_date') }}</th>
                                    <th class="px-10 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($overdueTasks as $task)
                                    <tr class="hover:bg-amber-50/30 transition-all duration-300">
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <span class="text-base font-black text-secondary">{{ $task->title }}</span>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <span class="text-sm font-bold text-gray-600">{{ $task->employee?->name ?? __('messages.unassigned') }}</span>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <span class="text-sm font-black text-rose-500">{{ $task->due_date?->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('client.tasks.edit', $task) }}" class="p-3 bg-gray-50 text-gray-400 hover:bg-secondary hover:text-white rounded-2xl transition-all duration-300 group/edit" title="{{ __('messages.edit') }}">
                                                    <svg class="w-5 h-5 group-hover/edit:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </a>
                                                <form action="{{ route('client.action-required.tasks.destroy', $task) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-3 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-2xl transition-all duration-300 group/del">
                                                        <svg class="w-5 h-5 group-hover/del:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-16 text-center text-gray-300 font-bold italic">{{ __('messages.no_overdue_tasks') ?? 'No overdue tasks found.' }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- 3. Returned Assets Section -->
            <section class="animate-in fade-in slide-in-from-bottom-4 duration-700 delay-200">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-2 h-8 bg-indigo-500 rounded-full"></div>
                    <h3 class="text-2xl font-black text-secondary tracking-tight">{{ __('messages.returned_assets') ?? 'Returned Assets/Assignments' }}</h3>
                    <span class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-xs font-black border border-indigo-100">{{ $returnedAssets->count() }}</span>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50/50 border-b border-gray-100">
                                <tr>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.asset_name') }}</th>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee') }}</th>
                                    <th class="px-10 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.returned_at') ?? 'Returned Date' }}</th>
                                    <th class="px-10 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($returnedAssets as $asset)
                                    <tr class="hover:bg-indigo-50/30 transition-all duration-300">
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-base font-black text-secondary">{{ $asset->type }}</span>
                                                <span class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">{{ $asset->serial_number ?: '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <span class="text-sm font-bold text-gray-600">{{ $asset->employee?->name ?? __('messages.unassigned') }}</span>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap">
                                            <span class="text-sm font-black text-indigo-600">{{ $asset->returned_date?->format('d/m/Y') }}</span>
                                        </td>
                                        <td class="px-10 py-6 whitespace-nowrap text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                            <div class="flex items-center justify-end gap-3">
                                                <form action="{{ route('client.action-required.assets.destroy', $asset) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-3 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-2xl transition-all duration-300 group/del">
                                                        <svg class="w-5 h-5 group-hover/del:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-16 text-center text-gray-300 font-bold italic">{{ __('messages.no_returned_assets') ?? 'No recently returned assets.' }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
