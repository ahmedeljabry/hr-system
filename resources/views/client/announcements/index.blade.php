@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        
        <!-- Standard Header -->
        <x-dashboard-sub-header 
            :title="__('messages.announcements')" 
            :subtitle="__('messages.announcements_desc')"
        >
            <x-slot name="actions">
                <a href="{{ route('client.announcements.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/add">
                    <svg class="w-4 h-4 me-2 group-hover/add:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('messages.create_announcement') }}
                </a>
            </x-slot>
        </x-dashboard-sub-header>


@if(session('success'))
    <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-[1.5rem] flex items-center shadow-sm animate-fade-in-down">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center mr-4 rtl:ml-4 rtl:mr-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden transition-all duration-500">
    <div class="overflow-x-auto">
        <table class="w-full text-left rtl:text-right border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('messages.task_title_label') }}</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('messages.generated_on') }}</th>
                    <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right rtl:text-left">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($announcements as $announcement)
                    <tr class="hover:bg-primary/5 transition-all duration-300 group">
                        <td class="px-10 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-secondary mb-1 group-hover:text-primary transition-colors italic">{{ $announcement->title }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-[11px] text-gray-400 font-medium line-clamp-1">{{ Str::limit(strip_tags($announcement->body), 100) }}</span>
                                    @if($announcement->attachments && count($announcement->attachments) > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($announcement->attachments as $path)
                                                <a href="{{ Storage::url($path) }}" target="_blank" class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-primary/10 text-primary text-[9px] font-black uppercase tracking-widest hover:bg-primary hover:text-secondary transition-all" title="{{ basename($path) }}">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    {{ count($announcement->attachments) > 1 ? basename($path) : __('messages.download') }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-primary/20 group-hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-xs font-bold text-gray-500 italic">{{ $announcement->published_at->format('M d, Y') }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-6 text-right rtl:text-left">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('client.announcements.edit', $announcement) }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-secondary hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('client.announcements.destroy', $announcement) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-0.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-10 py-32 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-secondary tracking-tight mb-2">{{ __('messages.no_announcements_yet') }}</h3>
                                <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mb-8">{{ __('messages.no_announcements') }}</p>
                                <a href="{{ route('client.announcements.create') }}" class="inline-flex items-center gap-2 text-secondary font-black text-xs uppercase tracking-widest hover:text-secondary/80 transition-colors">
                                    + {{ __('messages.create_announcement') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($announcements->hasPages())
        <div class="px-10 py-6 border-t border-gray-50 bg-gray-50/30">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
    </div>
</div>
@endsection
