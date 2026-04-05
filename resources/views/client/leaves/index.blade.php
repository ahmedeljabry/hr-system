@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.leave_management') ?? __('Leave Management') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('messages.leave_management_desc') ?? 'Review and oversee employee leave requests with professional administrative control.' }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.leaves.types') }}" 
                       class="inline-flex items-center px-10 py-4 bg-primary hover:bg-[#8affaa] text-secondary text-sm font-black rounded-2xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/config">
                        <svg class="w-5 h-5 me-3 group-hover/config:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('messages.config_leave_types') ?? __('Config Leave Types') }}
                    </a>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-2rem] right-[-2rem] w-48 h-48 bg-primary opacity-5 rounded-full transition-transform duration-700 group-hover:scale-110"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-24 h-24 bg-primary opacity-5 rounded-full transition-transform duration-500 group-hover:-translate-y-4"></div>
        </div>

        @if($pendingCount > 0)
        <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-amber-50 border border-amber-100 p-5 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-amber-800 font-bold tracking-tight">{{ __(':count pending leave request(s) require your attention.', ['count' => $pendingCount]) }}</p>
            </div>
        </div>
        @endif

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

        <!-- Status Filter Tabs -->
        <div class="mb-8 flex items-center gap-2 flex-wrap">
            @php
                $currentStatus = request('status');
                $tabs = [
                    null => ['label' => __('All'), 'active' => 'bg-secondary text-white', 'inactive' => 'bg-white text-gray-500 hover:bg-gray-50'],
                    'pending' => ['label' => __('Pending'), 'active' => 'bg-amber-500 text-white', 'inactive' => 'bg-white text-amber-600 hover:bg-amber-50'],
                    'approved' => ['label' => __('Approved'), 'active' => 'bg-primary text-secondary', 'inactive' => 'bg-white text-gray-500 hover:bg-primary/10 hover:text-secondary'],
                    'rejected' => ['label' => __('Rejected'), 'active' => 'bg-red-500 text-white', 'inactive' => 'bg-white text-red-500 hover:bg-red-50'],
                ];
            @endphp
            @foreach($tabs as $val => $tab)
                <a href="{{ route('client.leaves.index', $val ? ['status' => $val] : []) }}" 
                   class="px-8 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-sm transition-all duration-300 {{ $currentStatus === $val ? $tab['active'] . ' shadow-lg scale-105' : $tab['inactive'] . ' border border-gray-100' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden transition-all duration-500">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee') }}</th>
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.leave_type') }}</th>
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.period') }}</th>
                            <th class="px-8 py-6 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.duration') }}</th>
                            <th class="px-8 py-6 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                            <th class="px-8 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($requests as $req)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-secondary/5 flex items-center justify-center text-secondary font-black">
                                            {{ substr($req->employee->name, 0, 1) }}
                                        </div>
                                        <div class="text-base font-black text-secondary tracking-tight">{{ $req->employee->name }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                                        <span class="text-sm font-bold text-gray-600">{{ $req->leaveType ? __('messages.' . strtolower(str_replace(' ', '_', $req->leaveType->name))) ?? __($req->leaveType->name) : __('N/A') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-black text-secondary/70">
                                        {{ $req->start_date->format('M d, Y') }} — {{ $req->end_date->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-black tracking-tight">
                                        {{ $req->days_count }} {{ __('messages.days') }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                        ][$req->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $statusConfig }}">
                                        {{ __('messages.' . $req->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    @if($req->isPending())
                                        <div class="flex items-center justify-end gap-3 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}">
                                             <form action="{{ route('client.leaves.approve', $req) }}" method="POST" class="inline">
                                                  @csrf
                                                  <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-[#8affaa] text-secondary text-xs font-black rounded-xl shadow-lg shadow-primary/20 border-b-2 border-emerald-400 hover:border-emerald-300 transition-all duration-300 hover:-translate-y-1 active:translate-y-0.5 active:border-b-0 uppercase tracking-wider group/approve">
                                                      <span class="flex items-center gap-2">
                                                          <svg class="h-4 w-4 group-hover/approve:scale-125 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                          {{ __('messages.approve') ?? __('Approve') }}
                                                      </span>
                                                  </button>
                                              </form>
                                             <form action="{{ route('client.leaves.reject', $req) }}" method="POST" class="inline">
                                                 @csrf
                                                 <button type="submit" class="p-2.5 text-red-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-300" title="{{ __('messages.reject') ?? __('Reject') }}">
                                                     <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                 </button>
                                             </form>
                                         </div>
                                     @else
                                         <div class="flex flex-col items-end {{ app()->getLocale() == 'ar' ? 'items-start' : '' }} opacity-50">
                                             <span class="text-[9px] font-black uppercase text-gray-400 tracking-[0.1em] mb-1 italic">{{ __('messages.decision_recorded') }}</span>
                                             <span class="text-[10px] text-secondary font-bold">{{ $req->reviewed_at?->format('M d, Y') }}</span>
                                         </div>
                                     @endif
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_leave_requests') ?? __('No Leave Requests Found') }}</h3>
                                        <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_leave_requests_desc') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($requests->hasPages())
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
