@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <x-dashboard-sub-header
            :title="__('messages.leave_management')"
            :subtitle="__('messages.leave_management_desc')"
        >
            <x-slot name="actions">
                <a href="{{ route('client.leaves.types') }}"
                   class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-black rounded-xl backdrop-blur-md transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/config">
                    <svg class="w-4 h-4 me-2 group-hover/config:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('messages.config_leave_types') }}
                </a>
            </x-slot>
        </x-dashboard-sub-header>

        @if($pendingCount > 0)
            <div class="mb-6 bg-amber-50 border border-amber-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <p class="text-amber-800 font-bold tracking-tight">{{ __('messages.leave_requests_need_review', ['count' => $pendingCount]) }}</p>
                    <a href="{{ route('client.leaves.index', ['status' => 'pending']) }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 transition-all duration-300">
                        {{ __('messages.manage_request') }}
                    </a>
                </div>
            </div>
        @endif

        @if($resumptionCount > 0)
            <div class="mb-8 bg-blue-50 border border-blue-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <p class="text-blue-800 font-bold tracking-tight">{{ __('messages.leave_requests_need_resumption', ['count' => $resumptionCount]) }}</p>
                    <a href="{{ route('client.leaves.index', ['needs_resumption' => 1]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all duration-300">
                        {{ __('messages.manage_request') }}
                    </a>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-8">
                <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8">
                <div class="bg-red-50 border border-red-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-red-800 font-bold tracking-tight">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @php
            $currentStatus = $filters['status'] ?? null;
            $tabs = [
                null => ['label' => __('messages.all'), 'active' => 'bg-indigo-600 text-white shadow-indigo-100', 'inactive' => 'bg-white text-gray-500 hover:bg-gray-50'],
                'pending' => ['label' => __('messages.pending'), 'active' => 'bg-amber-500 text-white shadow-amber-100', 'inactive' => 'bg-white text-amber-600 hover:bg-amber-50'],
                'approved' => ['label' => __('messages.approved'), 'active' => 'bg-emerald-500 text-white shadow-emerald-100', 'inactive' => 'bg-white text-emerald-600 hover:bg-emerald-50'],
                'postponed' => ['label' => __('messages.postponed'), 'active' => 'bg-blue-500 text-white shadow-blue-100', 'inactive' => 'bg-white text-blue-600 hover:bg-blue-50'],
                'rejected' => ['label' => __('messages.rejected'), 'active' => 'bg-rose-500 text-white shadow-rose-100', 'inactive' => 'bg-white text-rose-500 hover:bg-rose-50'],
            ];
        @endphp

        <div class="mb-6 flex items-center gap-3 flex-wrap">
            @foreach($tabs as $value => $tab)
                <a href="{{ route('client.leaves.index', array_filter(array_merge(request()->except('page', 'status'), ['status' => $value]), fn ($item) => $item !== null && $item !== '')) }}"
                   class="px-8 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.15em] shadow-sm transition-all duration-300 {{ $currentStatus === $value ? $tab['active'] . ' shadow-xl scale-105 border-transparent' : $tab['inactive'] . ' border border-gray-100' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden">
            <div class="px-10 py-8 border-b border-gray-100">
                <form method="GET" action="{{ route('client.leaves.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[0.15em] mb-2">{{ __('messages.search') }}</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-secondary focus:border-primary focus:ring-primary/20" placeholder="{{ __('messages.search_employees') }}">
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
                    @if($currentStatus)
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee') }}</th>
                            <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.leave_type') }}</th>
                            <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.period') }}</th>
                            <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.duration') }}</th>
                            <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                            <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.notes') }}</th>
                            <th class="px-10 py-7 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($requests as $req)
                            @php
                                $displayName = $req->employee ? $req->employee->name : __('messages.deleted_employee');
                                $typeKey = 'messages.' . strtolower(str_replace(' ', '_', $req->leaveType->name));
                                $typeName = Lang::has($typeKey) ? __($typeKey) : $req->leaveType->name;
                                $statusClass = [
                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'postponed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                ][$req->status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                            @endphp
                            <tr class="hover:bg-primary/5 transition-all duration-300 group/row">
                                <td class="px-10 py-7 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <x-avatar :name="$displayName" size="md" class="rounded-2xl shadow-sm border border-gray-100 group-hover/row:border-primary transition-all" />
                                        <div>
                                            <div class="text-base font-black text-secondary tracking-tight capitalize group-hover/row:text-primary transition-colors">{{ $displayName }}</div>
                                            @if($req->employee?->position)
                                                <div class="text-[11px] text-gray-400 font-bold mt-1">{{ $req->employee->position }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full bg-primary opacity-50"></span>
                                        <span class="text-sm font-bold text-gray-600">{{ $typeName }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap">
                                    <div class="text-sm font-black text-secondary/70">
                                        {{ $req->start_date->translatedFormat('d M, Y') }} — {{ $req->end_date->translatedFormat('d M, Y') }}
                                    </div>
                                    @if($req->resumption_at)
                                        <div class="text-[10px] font-bold text-blue-500 mt-1">
                                            {{ __('messages.resumption_at') }}: {{ $req->resumption_at->format('d M Y H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap text-center">
                                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-600">
                                        {{ $req->days_count }} {{ __('messages.days') }}
                                    </span>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap text-center">
                                    <div class="space-y-2">
                                        <span class="inline-flex px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                            {{ __('messages.' . $req->status) }}
                                        </span>
                                        @if($req->requiresResumption())
                                            <div class="text-[10px] font-black uppercase tracking-widest text-blue-600">
                                                {{ __('messages.needs_resumption') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-10 py-7">
                                    @if($req->reviewer_comment)
                                        <p class="text-sm text-gray-600 leading-6">{{ Str::limit($req->reviewer_comment, 80) }}</p>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                    <div class="flex items-center justify-end gap-3 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}">
                                        @if(in_array($req->status, ['pending', 'postponed']))
                                            <form action="{{ route('client.leaves.approve', $req) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-primary text-secondary hover:bg-primary/90 text-[10px] font-black rounded-xl transition-all duration-300 uppercase tracking-widest">
                                                    {{ __('messages.approve') }}
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('client.leaves.edit', $req) }}" class="inline-flex items-center px-4 py-2 bg-secondary text-white hover:bg-secondary/90 text-[10px] font-black rounded-xl transition-all duration-300 uppercase tracking-widest">
                                            {{ __('messages.manage_request') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-10 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_leave_requests') }}</h3>
                                        <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_leave_requests_desc') }}</p>
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
</div>
@endsection
