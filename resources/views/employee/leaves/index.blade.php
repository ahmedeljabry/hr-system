@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <!-- Standard Header -->
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

        @if($currentLeave)
            @php
                $currentTypeKey = 'messages.' . strtolower(str_replace(' ', '_', $currentLeave->leaveType->name));
                $currentTypeName = Lang::has($currentTypeKey) ? __($currentTypeKey) : $currentLeave->leaveType->name;
            @endphp
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="rounded-[2rem] border border-amber-100 bg-gradient-to-r from-amber-50 via-orange-50 to-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-amber-900">
                                    {{ $currentLeave->isCurrentlyOnLeave() ? __('messages.currently_on_leave_banner_title') : __('messages.leave_return_required_title') }}
                                </h3>
                                <p class="mt-1 text-sm font-bold text-amber-800/90">
                                    @if($currentLeave->isCurrentlyOnLeave())
                                        {{ __('messages.currently_on_leave_banner_message', [
                                            'type' => $currentTypeName,
                                            'start' => $currentLeave->start_date->translatedFormat('d M Y'),
                                            'end' => $currentLeave->end_date->translatedFormat('d M Y'),
                                        ]) }}
                                    @else
                                        {{ __('messages.leave_return_required_message', [
                                            'end' => $currentLeave->end_date->translatedFormat('d M Y'),
                                        ]) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if($currentLeave->canEmployeeRecordResumption())
                            <form action="{{ route('employee.leaves.resume', $currentLeave) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-6 py-3 text-xs font-black uppercase tracking-[0.2em] text-white transition-all duration-300 hover:-translate-y-1 hover:bg-amber-600">
                                    {{ __('messages.record_return_to_work') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif


	        @if(session('success'))
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-emerald-800 font-bold tracking-tight">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="bg-red-50 border border-red-100 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-red-800 font-bold tracking-tight">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Leave Balance Cards -->
        @if(count($balanceSummary) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $colors = ['blue', 'purple', 'emerald', 'amber', 'rose', 'indigo'];
            @endphp
            @foreach($balanceSummary as $index => $balance)
                @php
                    $color = $colors[$index % count($colors)];
                    $pct = $balance['max_days'] > 0 ? min(100, ($balance['used_days'] / $balance['max_days']) * 100) : 0;
                @endphp
                <div class="group bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-100 p-6 hover:shadow-[0_20px_50px_rgba(0,0,0,0.06)] hover:translate-y-[-4px] transition-all duration-500">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $color }}-50 flex items-center justify-center text-{{ $color }}-500 group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if(str_contains(strtolower($balance['type']->name), 'sick') || str_contains(strtolower($balance['type']->name), 'مرض'))
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                @elseif(str_contains(strtolower($balance['type']->name), 'annual') || str_contains(strtolower($balance['type']->name), 'سنوي'))
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @endif
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 mb-1">{{ __('messages.remaining') }}</p>
                            <p class="text-2xl font-black text-gray-900 leading-none">{{ $balance['remaining'] }} <span class="text-xs font-bold text-gray-400">{{ __('messages.days') }}</span></p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-xs font-bold">
                            @php
                                $cTypeKey = 'messages.' . strtolower(str_replace(' ', '_', $balance['type']->name));
                                $cTypeName = Lang::has($cTypeKey) ? __($cTypeKey) : $balance['type']->name;
                            @endphp
                            <span class="text-secondary tracking-tight">{{ $cTypeName }}</span>
                            <span class="text-{{ $color }}-600 bg-{{ $color }}-50 px-3 py-1 rounded-full outline outline-1 outline-{{ $color }}-100">{{ $balance['used_days'] }} / {{ $balance['max_days'] > 0 ? $balance['max_days'] : '∞' }}</span>
                        </div>
                        
                        <div class="relative w-full h-2.5 bg-gray-50 rounded-full overflow-hidden border border-gray-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-{{ $color }}-400 to-{{ $color }}-600 transition-all duration-1000 shadow-sm" style="width: {{ $pct }}%"></div>
                        </div>
                        
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('messages.used') }} {{ $balance['used_days'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="mb-10 bg-white rounded-3xl p-12 text-center border border-dashed border-gray-200">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-xl font-black text-secondary mb-2">{{ __('messages.no_leave_types') }}</h3>
            <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_leave_types_desc') }}</p>
        </div>
        @endif

        <!-- Leave Request History Table -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
            <div class="px-10 py-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
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

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/30 border-b border-gray-50">
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.leave_type') }}</th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.dates') }}</th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.duration') }}</th>
                        <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.return_to_work') }}</th>
                        <th class="px-10 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.reviewer_comment') }}</th>
                        <th class="px-10 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($requests as $req)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300">
                                <td class="px-10 py-7 whitespace-nowrap">
                                    @php
                                        $rTypeKey = 'messages.' . strtolower(str_replace(' ', '_', $req->leaveType->name));
                                        $rTypeName = Lang::has($rTypeKey) ? __($rTypeKey) : $req->leaveType->name;
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="w-1.5 h-6 bg-blue-500 rounded-full opacity-40"></div>
                                        <span class="text-base font-black text-secondary tracking-tight capitalize">{{ $rTypeName }}</span>
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
                                    @php
                                        $sc = [
                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                        ][$req->status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                                    @endphp
                                    <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $sc }}">
                                        {{ __($req->status) }}
                                    </span>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap">
                                    @if($req->resumed_at)
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-secondary/80">
                                                {{ $req->resumed_at->translatedFormat('d M Y h:i A') }}
                                            </span>
                                            @if($req->resumption_recorded_at)
                                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                                    {{ __('messages.resumption_recorded_at') }}: {{ $req->resumption_recorded_at->translatedFormat('d M Y h:i A') }}
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($req->requiresResumption())
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black uppercase tracking-[0.18em] text-amber-600">
                                                {{ $req->isCurrentlyOnLeave() ? __('messages.currently_on_leave') : __('messages.return_pending') }}
                                            </span>
                                            <span class="text-sm font-bold text-gray-500">
                                                {{ $req->isCurrentlyOnLeave()
                                                    ? __('messages.currently_on_leave_banner_message', ['type' => $rTypeName, 'start' => $req->start_date->translatedFormat('d M Y'), 'end' => $req->end_date->translatedFormat('d M Y')])
                                                    : __('messages.leave_return_required_message', ['end' => $req->end_date->translatedFormat('d M Y')]) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-10 py-7 text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                    @if($req->reviewer_comment)
                                        <span class="text-sm text-gray-500 font-medium italic">"{{ Str::limit($req->reviewer_comment, 40) }}"</span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-10 py-7 text-right {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">
                                    @if($req->canEmployeeRecordResumption())
                                        <form action="{{ route('employee.leaves.resume', $req) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-5 py-3 text-[10px] font-black uppercase tracking-[0.18em] text-white transition-all duration-300 hover:-translate-y-1 hover:bg-amber-600">
                                                {{ __('messages.record_return_to_work') }}
                                            </button>
                                        </form>
                                    @elseif($req->resumed_at)
                                        <span class="inline-flex items-center rounded-xl bg-emerald-50 px-4 py-2 text-[10px] font-black uppercase tracking-[0.18em] text-emerald-700">
                                            {{ __('messages.return_recorded') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-10 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
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
