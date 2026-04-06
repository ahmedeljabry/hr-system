@extends('layouts.employee')

@section('content')
<div class="space-y-10">
    <!-- Premium Hero Section -->
    <div class="bg-secondary rounded-[2.5rem] shadow-xl p-10 text-white relative overflow-hidden group">
        <div class="relative z-10 text-center md:text-left {{ app()->getLocale() == 'ar' ? 'md:text-right' : '' }}">
            <h1 class="text-4xl font-black mb-2 tracking-tight">{{ __('My Assets') }}</h1>
            <p class="text-primary text-lg opacity-90 font-medium">{{ __('View and manage company equipment in your custody.') }}</p>
        </div>
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 text-white opacity-10 group-hover:scale-110 transition-transform duration-1000">
            <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24"><path d="M22 19h-6v-4h6v4zm-11.5 0h-4.5c-1.1 0-2-.9-2-2v-11c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v2.5c0 .3-.2.5-.5.5s-.5-.2-.5-.5v-2.5c0-.6-.4-1-1-1h-14c-.6 0-1 .4-1 1v11c0 .6.4 1 1 1h4.5c.3 0 .5.2.5.5s-.2.5-.5.5zm11.5-6h-6v-4h6v4zm0-6h-6v-4h6v4zm-14.5 9v-11h3v11h-3z"/></svg>
        </div>
    </div>

    <!-- Assets Table Container -->
    <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Asset') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Serial Number') }}
                        </th>
                        <th class="px-10 py-7 text-left text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            {{ __('Assigned Date') }}
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
                                    <h3 class="text-2xl font-black text-secondary tracking-tight mb-2">{{ __('No assets found.') }}</h3>
                                    <p class="text-sm text-gray-400 max-w-xs mx-auto">{{ __('No company equipment is currently assigned to you.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

