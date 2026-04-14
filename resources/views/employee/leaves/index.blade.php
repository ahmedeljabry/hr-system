@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <x-dashboard-sub-header
        :title="__('messages.my_leaves')"
        :subtitle="__('messages.view_leave_balance_desc')"
    >
        <x-slot name="actions">
            <a href="{{ route('employee.leaves.create') }}"
               class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/add">
                <svg class="w-4 h-4 me-2 group-hover/add:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.request_leave') }}
            </a>
        </x-slot>
    </x-dashboard-sub-header>

    @if(session('success'))
        <div class="mb-8">
            <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8">
            <div class="bg-red-50 border border-red-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-red-800 font-bold tracking-tight">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($pendingResumptionLeave)
        <div class="mb-8 bg-amber-50 border border-amber-100 rounded-[2rem] p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-black text-amber-900 mb-1">{{ __('messages.leave_pending_resumption') }}</h3>
                    <p class="text-sm text-amber-800">
                        {{ __('messages.leave_pending_resumption_desc', [
                            'type' => $pendingResumptionLeave->leaveType->name,
                            'start' => $pendingResumptionLeave->start_date->format('d M Y'),
                            'end' => $pendingResumptionLeave->end_date->format('d M Y'),
                            'days' => $pendingResumptionLeave->days_count,
                        ]) }}
                    </p>
                </div>
                <form action="{{ route('employee.leaves.resume', $pendingResumptionLeave) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('messages.end_leave') }}
                    </button>
                </form>
            </div>
        </div>
    @elseif($currentLeave)
        <div class="mb-8 bg-emerald-50 border border-emerald-100 rounded-[2rem] p-6 shadow-sm">
            <h3 class="text-lg font-black text-emerald-900 mb-1">{{ __('messages.leave_currently_active') }}</h3>
            <p class="text-sm text-emerald-800">
                {{ __('messages.leave_currently_active_desc', [
                    'type' => $currentLeave->leaveType->name,
                    'start' => $currentLeave->start_date->format('d M Y'),
                    'end' => $currentLeave->end_date->format('d M Y'),
                    'days' => $currentLeave->days_count,
                    'remaining' => $currentLeave->remainingLeaveDays(),
                ]) }}
            </p>
        </div>
    @endif

    @php
        $balanceColors = ['blue', 'purple', 'emerald', 'amber', 'rose', 'indigo'];
    @endphp

    @if(!empty($balanceSummary))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @foreach($balanceSummary as $index => $balance)
                @php
                    $color = $balanceColors[$index % count($balanceColors)];
                    $pct = $balance['max_days'] > 0 ? min(100, ($balance['used_days'] / $balance['max_days']) * 100) : 0;
                    $typeKey = 'messages.' . strtolower(str_replace(' ', '_', $balance['type']->name));
                    $typeName = \Illuminate\Support\Facades\Lang::has($typeKey) ? __($typeKey) : $balance['type']->name;
                @endphp
                <div class="group bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 p-6 hover:shadow-[0_20px_50px_rgba(0,0,0,0.06)] hover:translate-y-[-4px] transition-all duration-500">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $color }}-50 flex items-center justify-center text-{{ $color }}-500 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 mb-1">{{ __('messages.remaining') }}</p>
                            <p class="text-2xl font-black text-gray-900 leading-none">{{ $balance['remaining'] }} <span class="text-xs font-bold text-gray-400">{{ __('messages.days') }}</span></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-secondary tracking-tight">{{ $typeName }}</span>
                            <span class="text-{{ $color }}-600 bg-{{ $color }}-50 px-3 py-1 rounded-full outline outline-1 outline-{{ $color }}-100">
                                {{ $balance['used_days'] }} / {{ $balance['max_days'] > 0 ? $balance['max_days'] : '∞' }}
                            </span>
                        </div>

                        <div class="relative w-full h-2.5 bg-gray-50 rounded-full overflow-hidden border border-gray-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-{{ $color }}-400 to-{{ $color }}-600 transition-all duration-1000 shadow-sm" style="width: {{ $pct }}%"></div>
                        </div>

                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('messages.used') }} {{ $balance['used_days'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
        <div class="px-10 py-8 border-b border-gray-50 flex flex-col gap-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-black text-secondary leading-none mb-2">{{ __('messages.request_history') }}</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.2em]">{{ __('messages.track_your_submissions') }}</p>
                </div>
                @if($requests->total() > 0)
                    <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border border-blue-100">
                        {{ $requests->total() }} {{ __('messages.total_requests_count') }}
                    </span>
                @endif
            </div>

            <form method="GET" action="{{ route('employee.leaves.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.status') }}</label>
                    <select name="status" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20">
                        <option value="">{{ __('messages.all') }}</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>{{ __('messages.pending') }}</option>
                        <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>{{ __('messages.approved') }}</option>
                        <option value="postponed" @selected(($filters['status'] ?? '') === 'postponed')>{{ __('messages.postponed') }}</option>
                        <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>{{ __('messages.rejected') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.sort_by') }}</label>
                    <select name="sort" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20">
                        <option value="created_at" @selected(($filters['sort'] ?? 'created_at') === 'created_at')>{{ __('messages.submitted_at') }}</option>
                        <option value="start_date" @selected(($filters['sort'] ?? '') === 'start_date')>{{ __('messages.start_date') }}</option>
                        <option value="end_date" @selected(($filters['sort'] ?? '') === 'end_date')>{{ __('messages.end_date') }}</option>
                        <option value="status" @selected(($filters['sort'] ?? '') === 'status')>{{ __('messages.status') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.direction') }}</label>
                    <select name="direction" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20">
                        <option value="desc" @selected(($filters['direction'] ?? 'desc') === 'desc')>{{ __('messages.newest') }}</option>
                        <option value="asc" @selected(($filters['direction'] ?? '') === 'asc')>{{ __('messages.oldest') }}</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 bg-secondary hover:bg-secondary/90 text-white text-xs font-black rounded-2xl transition-all duration-300">
                        {{ __('messages.filter') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/30 border-b border-gray-50">
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.leave_type') }}</th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.dates') }}</th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.duration') }}</th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.resumption_at') }}</th>
                        <th class="px-10 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                        @php
                            $typeKey = 'messages.' . strtolower(str_replace(' ', '_', $req->leaveType->name));
                            $typeName = Lang::has($typeKey) ? __($typeKey) : $req->leaveType->name;
                            $statusClass = [
                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'postponed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'rejected' => 'bg-red-100 text-red-700 border-red-200',
                            ][$req->status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-all duration-300">
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-blue-500 rounded-full opacity-40"></div>
                                    <div>
                                        <div class="text-base font-black text-secondary tracking-tight capitalize">{{ $typeName }}</div>
                                        @if($req->reviewer_comment)
                                            <div class="text-[11px] text-gray-400 mt-1">{{ Str::limit($req->reviewer_comment, 45) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-secondary/80 leading-none mb-1">
                                        {{ $req->start_date->format('d M Y') }} — {{ $req->end_date->format('d M Y') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $req->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-gray-100 text-secondary text-xs font-black tracking-tight">
                                    {{ $req->days_count }} {{ __('messages.days') }}
                                </span>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-center">
                                <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                    {{ __('messages.' . $req->status) }}
                                </span>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-center">
                                @if($req->resumption_at)
                                    <div class="text-sm font-black text-secondary">{{ $req->resumption_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold">{{ $req->resumption_at->format('H:i') }}</div>
                                @elseif($req->canEmployeeRecordResumption())
                                    <span class="inline-flex px-4 py-2 rounded-xl bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-widest border border-amber-200">
                                        {{ __('messages.leave_pending_resumption') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-10 py-7 text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                @if($req->canEmployeeRecordResumption())
                                    <form action="{{ route('employee.leaves.resume', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black rounded-xl transition-all duration-300">
                                            {{ __('messages.end_leave') }}
                                        </button>
                                    </form>
                                @elseif($req->reviewer_comment)
                                    <span class="text-sm text-gray-500 font-medium italic">"{{ Str::limit($req->reviewer_comment, 40) }}"</span>
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_requests_found') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_requests_found_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="px-10 py-8 bg-gray-50/50 border-t border-gray-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
