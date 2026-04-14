@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full space-y-8">
        <x-dashboard-sub-header
            :title="__('messages.manage_request')"
            :subtitle="__('messages.leave_management_desc')"
        >
            <x-slot name="actions">
                <a href="{{ route('client.leaves.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-black rounded-xl backdrop-blur-md transition-all duration-300">
                    {{ __('messages.back_to_requests') }}
                </a>
            </x-slot>
        </x-dashboard-sub-header>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-red-800 font-bold tracking-tight">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-100 rounded-3xl p-6">
                <ul class="space-y-2 text-sm font-bold text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
                <div class="px-10 py-8 border-b border-gray-50">
                    <h3 class="text-xl font-black text-secondary mb-2">{{ __('messages.edit_leave') }}</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.2em]">{{ __('messages.leave_management_desc') }}</p>
                </div>

                <form action="{{ route('client.leaves.update', $leaveRequest) }}" method="POST" class="p-10 space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.employee') }}</label>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-black text-secondary">
                                {{ $leaveRequest->employee?->name ?? __('messages.deleted_employee') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.status') }}</label>
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-black text-secondary">
                                {{ __('messages.' . $leaveRequest->status) }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="leave_type_id" class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.leave_type') }}</label>
                            <select id="leave_type_id" name="leave_type_id" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20">
                                @foreach($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->id }}" @selected(old('leave_type_id', $leaveRequest->leave_type_id) == $leaveType->id)>{{ $leaveType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.start_date') }}</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $leaveRequest->start_date->format('Y-m-d')) }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20">
                        </div>
                        <div>
                            <label for="end_date" class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.end_date') }}</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $leaveRequest->end_date->format('Y-m-d')) }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20">
                        </div>
                    </div>

                    <div>
                        <label for="reason" class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.reason') }}</label>
                        <textarea id="reason" name="reason" rows="4" class="w-full rounded-[2rem] border border-gray-200 bg-white px-5 py-4 text-sm font-medium text-secondary focus:border-primary focus:ring-primary/20">{{ old('reason', $leaveRequest->reason) }}</textarea>
                    </div>

                    <div>
                        <label for="reviewer_comment" class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.review_notes') }}</label>
                        <textarea id="reviewer_comment" name="reviewer_comment" rows="4" class="w-full rounded-[2rem] border border-gray-200 bg-white px-5 py-4 text-sm font-medium text-secondary focus:border-primary focus:ring-primary/20">{{ old('reviewer_comment', $leaveRequest->reviewer_comment) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="resumption_at" class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.resumption_at') }}</label>
                            <input type="datetime-local" id="resumption_at" name="resumption_at" value="{{ old('resumption_at', optional($leaveRequest->resumption_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20">
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-3 text-sm font-bold text-secondary">
                                <input type="checkbox" name="clear_resumption" value="1" @checked(old('clear_resumption')) class="rounded border-gray-300 text-primary focus:ring-primary/20">
                                {{ __('messages.clear_resumption') }}
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="resumption_notes" class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.resumption_notes') }}</label>
                        <textarea id="resumption_notes" name="resumption_notes" rows="3" class="w-full rounded-[2rem] border border-gray-200 bg-white px-5 py-4 text-sm font-medium text-secondary focus:border-primary focus:ring-primary/20">{{ old('resumption_notes', $leaveRequest->resumption_notes) }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" name="status_action" value="keep" class="inline-flex items-center px-6 py-3 bg-secondary text-white hover:bg-secondary/90 text-xs font-black rounded-xl transition-all duration-300">
                            {{ __('messages.save_changes') }}
                        </button>
                        <button type="submit" name="status_action" value="approve" class="inline-flex items-center px-6 py-3 bg-primary text-secondary hover:bg-primary/90 text-xs font-black rounded-xl transition-all duration-300">
                            {{ __('messages.save_and_approve') }}
                        </button>
                        <button type="submit" name="status_action" value="postpone" class="inline-flex items-center px-6 py-3 bg-blue-500 text-white hover:bg-blue-600 text-xs font-black rounded-xl transition-all duration-300">
                            {{ __('messages.save_and_postpone') }}
                        </button>
                        <button type="submit" name="status_action" value="reject" class="inline-flex items-center px-6 py-3 bg-red-500 text-white hover:bg-red-600 text-xs font-black rounded-xl transition-all duration-300">
                            {{ __('messages.save_and_reject') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-8">
                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 p-8">
                    <h3 class="text-lg font-black text-secondary mb-4">{{ __('messages.leave_request_actions') }}</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-bold">{{ __('messages.duration') }}</span>
                            <span class="font-black text-secondary">{{ $leaveRequest->days_count }} {{ __('messages.days') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-bold">{{ __('messages.start_date') }}</span>
                            <span class="font-black text-secondary">{{ $leaveRequest->start_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-bold">{{ __('messages.end_date') }}</span>
                            <span class="font-black text-secondary">{{ $leaveRequest->end_date->format('d M Y') }}</span>
                        </div>
                        @if($leaveRequest->resumption_at)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 font-bold">{{ __('messages.resumption_at') }}</span>
                                <span class="font-black text-secondary">{{ $leaveRequest->resumption_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <h3 class="text-lg font-black text-secondary">{{ __('messages.leave_action_history') }}</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($leaveRequest->actions as $action)
                            <div class="px-8 py-6">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    <div>
                                        <div class="text-sm font-black text-secondary">{{ __('messages.' . $action->action) }}</div>
                                        <div class="text-[11px] text-gray-400 font-bold">{{ $action->actor_name }} • {{ $action->created_at->format('d M Y H:i') }}</div>
                                    </div>
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-black uppercase tracking-widest text-gray-500">
                                        {{ $action->actor_role }}
                                    </span>
                                </div>
                                @if($action->notes)
                                    <p class="text-sm text-gray-600 leading-6">{{ $action->notes }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="px-8 py-10 text-center text-sm font-bold text-gray-400">
                                {{ __('messages.no_leave_actions') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
