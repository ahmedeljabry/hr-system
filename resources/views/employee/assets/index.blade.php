@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <!-- Standard Header -->
    <x-dashboard-sub-header 
        :title="__('messages.my_assets')" 
        :subtitle="__('messages.my_assets_desc')"
    />


    <!-- Assets Table Container -->
    <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('messages.asset') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('messages.serial_number') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('messages.assigned_date') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-blue-50/20 transition-all duration-300 group/row">
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover/row:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-base font-black text-secondary tracking-tight group-hover/row:text-blue-600 transition-colors">{{ $asset->type }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $asset->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                <span class="px-4 py-2 bg-gray-100/50 rounded-xl text-xs font-black text-secondary font-mono tracking-wider border border-gray-100">
                                    {{ $asset->serial_number ?: '—' }}
                                </span>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-sm font-bold text-gray-500">
                                    <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ $asset->assigned_date->translatedFormat('d M, Y') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    </div>
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_assets_found') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('messages.no_assets_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
@endsection

