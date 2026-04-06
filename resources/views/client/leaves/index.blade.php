@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="w-full">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-xl rounded-[2.5rem] p-10 text-white mb-10 relative group">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black mb-2 tracking-tight text-white">{{ __('messages.leave_management') }}</h1>
                    <p class="text-primary text-lg opacity-90">{{ __('messages.leave_management_desc') }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.leaves.types') }}" 
                       class="inline-flex items-center px-10 py-5 bg-white/10 backdrop-blur-md border-2 border-white/20 hover:bg-white/20 text-white text-sm font-black rounded-2xl shadow-xl transition-all duration-500 hover:-translate-y-2 active:scale-95 group/config">
                        <svg class="w-5 h-5 me-3 group-hover/config:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('messages.config_leave_types') }}
                    </a>
                </div>
            </div>
            
            <!-- Animated decorative overlays -->
            <div class="absolute top-[-3rem] right-[-3rem] w-64 h-64 bg-white opacity-5 rounded-full transition-transform duration-1000 group-hover:scale-125"></div>
            <div class="absolute bottom-[-1rem] left-[10%] w-32 h-32 bg-indigo-400 opacity-10 rounded-full transition-transform duration-700 group-hover:-translate-y-8"></div>
        </div>

        @if($pendingCount > 0)
        <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-amber-50 border border-amber-100 p-5 rounded-3xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-amber-800 font-bold tracking-tight">{{ __(':count pending leave request(s) require your attention.', ['count' => $pendingCount]) }}</p>
            </div>
        </div>
        @endif

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

        <!-- Status Filter Tabs -->
        <div class="mb-10 flex items-center gap-3 flex-wrap">
            @php
                $currentStatus = request('status');
                $tabs = [
                    null => ['label' => __('messages.all'), 'active' => 'bg-indigo-600 text-white shadow-indigo-100', 'inactive' => 'bg-white text-gray-500 hover:bg-gray-50'],
                    'pending' => ['label' => __('messages.pending'), 'active' => 'bg-amber-500 text-white shadow-amber-100', 'inactive' => 'bg-white text-amber-600 hover:bg-amber-50'],
                    'approved' => ['label' => __('messages.approved'), 'active' => 'bg-emerald-500 text-white shadow-emerald-100', 'inactive' => 'bg-white text-emerald-600 hover:bg-emerald-50'],
                    'rejected' => ['label' => __('messages.rejected'), 'active' => 'bg-rose-500 text-white shadow-rose-100', 'inactive' => 'bg-white text-rose-500 hover:bg-rose-50'],
                ];
            @endphp
            @foreach($tabs as $val => $tab)
                <a href="{{ route('client.leaves.index', $val ? ['status' => $val] : []) }}" 
                   class="px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.15em] shadow-sm transition-all duration-300 {{ $currentStatus === $val ? $tab['active'] . ' shadow-xl scale-105 border-transparent' : $tab['inactive'] . ' border border-gray-100' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.employee') }}</th>
                            <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.leave_type') }}</th>
                            <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.period') }}</th>
                            <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.duration') }}</th>
                            <th class="px-10 py-7 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') }}</th>
                            <th class="px-10 py-7 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($requests as $req)
                            <tr class="hover:bg-primary/5 transition-all duration-300 group/row">
                                <td class="px-10 py-7 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        @php
                                            $displayName = $req->employee ? $req->employee->name : __('messages.deleted_employee');
                                        @endphp
                                        <x-avatar :name="$displayName" size="md" class="rounded-2xl shadow-sm border border-gray-100 group-hover/row:border-primary transition-all" />
                                        <div class="text-base font-black text-secondary tracking-tight capitalize group-hover/row:text-primary transition-colors">{{ $displayName }}</div>
                                    </div>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full bg-primary opacity-50"></span>
                                        @php
                                            $typeKey = 'messages.' . strtolower(str_replace(' ', '_', $req->leaveType->name));
                                        @endphp
                                        <span class="text-sm font-bold text-gray-600">{{ Lang::has($typeKey) ? __($typeKey) : $req->leaveType->name }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap">
                                    <div class="text-sm font-black text-secondary/70">
                                        {{ $req->start_date->translatedFormat('d M, Y') }} — {{ $req->end_date->translatedFormat('d M, Y') }}
                                    </div>
                                    <div class="text-[9px] font-black uppercase text-gray-400 tracking-tighter">{{ $req->start_date->diffInDays($req->end_date) + 1 }} {{ __('messages.days') }}</div>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap text-center">
                                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest {{ 
                                        $req->status == 'approved' ? 'bg-primary/20 text-secondary border border-primary/30' : 
                                        ($req->status == 'rejected' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-gray-100 text-gray-500') 
                                    }}">
                                        {{ __('messages.' . $req->status) }}
                                    </span>
                                </td>
                                <td class="px-10 py-7 whitespace-nowrap text-center">
                                    @if($req->status == 'pending')
                                        <div class="flex items-center justify-end gap-3 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}">
                                             <form action="{{ route('client.leaves.approve', $req) }}" method="POST" class="inline">
                                                  @csrf
                                                  <button type="submit" class="px-8 py-3 bg-primary text-secondary hover:bg-primary/90 text-[10px] font-black rounded-xl shadow-xl shadow-primary/20 transition-all duration-300 hover:-translate-y-1 active:scale-95 uppercase tracking-widest group/approve">
                                                      <span class="flex items-center gap-2">
                                                          <svg class="h-4 w-4 group-hover/approve:scale-125 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                          {{ __('messages.approve') }}
                                                      </span>
                                                  </button>
                                              </form>
                                             <form action="{{ route('client.leaves.reject', $req) }}" method="POST" class="inline">
                                                 @csrf
                                                 <button type="submit" class="p-3 text-secondary/40 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all duration-300 group/reject" title="{{ __('messages.reject') }}">
                                                     <svg class="h-5 w-5 group-hover/reject:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                 </button>
                                             </form>
                                         </div>
                                     @else
                                         <div class="flex flex-col items-end {{ app()->getLocale() == 'ar' ? 'items-start' : '' }} opacity-50">
                                             <span class="text-[9px] font-black uppercase text-gray-400 tracking-[0.1em] mb-1 italic">{{ __('messages.decision_recorded') }}</span>
                                             <span class="text-[10px] text-secondary font-bold">{{ $req->reviewed_at?->translatedFormat('d M, Y') }}</span>
                                         </div>
                                     @endif
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-10 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
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
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
