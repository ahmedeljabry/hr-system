@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="w-full">
        
        <!-- Premium Hero Section -->
        <div class="bg-secondary overflow-hidden shadow-2xl rounded-3xl p-10 text-white mb-10 relative group border border-primary/20">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-extrabold mb-2 tracking-tight text-primary">{{ __('messages.assets') ?? __('Assets') }}</h1>
                    <p class="text-gray-300 text-lg opacity-90">{{ __('messages.assets_desc') ?? 'Track and manage organizational assets and equipment with precision.' }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.assets.create') }}" 
                       class="inline-flex items-center px-10 py-4 bg-primary hover:bg-[#8affaa] text-secondary text-sm font-black rounded-2xl shadow-[0_20px_50px_rgba(var(--color-primary-rgb),0.3)] hover:shadow-[0_25px_60px_rgba(var(--color-primary-rgb),0.5)] border-b-4 border-emerald-400 hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2 active:translate-y-1 active:border-b-0 group/add">
                        <svg class="w-5 h-5 me-3 group-hover/add:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('messages.add_asset') ?? __('Add Asset') }}
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
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.asset_name') ?? __('Asset') }}</th>
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.serial_number') ?? __('Serial Number') }}</th>
                            <th class="px-8 py-6 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">{{ __('messages.assigned_employee') ?? __('Custodian') }}</th>
                            <th class="px-8 py-6 text-center text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('messages.status') ?? __('Status') }}</th>
                            <th class="px-8 py-6 text-right text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-left' : '' }}">{{ __('messages.actions') ?? __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-gray-50/50 transition-all duration-300">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-secondary/5 flex items-center justify-center text-secondary font-black">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-base font-black text-secondary tracking-tight">{{ $asset->type }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest truncate max-w-xs">{{ $asset->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span class="px-4 py-1.5 rounded-lg bg-gray-100 text-secondary font-mono text-xs font-bold ring-1 ring-gray-200">
                                        {{ $asset->serial_number ?: '-' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @php
                                            $displayName = $asset->employee ? $asset->employee->name : __('messages.available');
                                        @endphp
                                        <span class="text-sm font-bold text-gray-600">{{ $displayName ?: __('messages.inventory') ?? __('Inventory') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-center">
                                    @if($asset->employee_id)
                                        <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-secondary text-white shadow-lg shadow-secondary/20">
                                            {{ __('Assigned') }}
                                        </span>
                                    @else
                                        <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-primary/20 text-secondary border border-primary/30">
                                            {{ __('Available') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-3 {{ app()->getLocale() == 'ar' ? 'justify-start' : '' }}">
                                        <a href="{{ route('client.assets.edit', $asset) }}" 
                                           class="p-2.5 text-gray-400 hover:text-secondary hover:bg-gray-100 rounded-xl transition-all duration-300 group/edit">
                                            <svg class="h-5 w-5 group-hover/edit:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('client.assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
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
                                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_assets') ?? __('No Assets Found') }}</h3>
                                        <p class="text-sm text-gray-400 max-w-xs mx-auto mb-6">{{ __('messages.assets_empty_desc') }}</p>
                                        <a href="{{ route('client.assets.create') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-black text-sm transition-colors">
                                            {{ __('messages.add_asset') }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($assets->hasPages())
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    {{ $assets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
