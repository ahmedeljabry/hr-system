@extends('layouts.employee')

@section('content')
<div class="pt-8 pb-12">
    <!-- Standard Header -->
    <x-dashboard-sub-header 
        :title="__('messages.announcements')" 
        :subtitle="__('messages.announcements_desc')"
    />


<div class="space-y-10">
    @forelse($announcements as $announcement)
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden group/card hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] transition-all duration-700">
            <div class="px-10 py-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center text-xs font-black uppercase tracking-widest text-gray-400">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span>{{ __('messages.notice') }}</span>
                    <span class="mx-2 opacity-20">|</span>
                    <span class="text-secondary opacity-60">{{ $announcement->published_at->translatedFormat('d M Y - h:i A') }}</span>
                </div>
                <div class="hidden sm:block opacity-60">
                    {{ $announcement->published_at->diffForHumans() }}
                </div>
            </div>
            
            <div class="p-10 md:p-14">
                <h2 class="text-2xl font-black text-secondary mb-6 group-hover/card:text-primary transition-colors duration-300 leading-tight">{{ $announcement->title }}</h2>
                <div class="prose max-w-none text-gray-500 font-medium leading-relaxed text-lg antialiased">
                    {!! nl2br(e($announcement->body)) !!}
                </div>
                
                <div class="mt-12 flex items-center justify-between border-t border-gray-50 pt-8">
                    <div class="flex -space-x-2">
                        <div class="w-10 h-10 rounded-full bg-secondary border-2 border-white flex items-center justify-center text-[10px] font-black text-white antialiased">HR</div>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover/card:rotate-12 transition-all duration-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-gray-100 p-20 text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-200">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 4v4h4"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-secondary tracking-tight mb-3">{{ __('messages.no_announcements_yet') }}</h3>
            <p class="text-gray-400 font-bold max-w-xs mx-auto text-sm">{{ __('messages.no_announcements_desc') }}</p>
        </div>
    @endforelse

    @if($announcements->hasPages())
        <div class="mt-12 p-8 bg-white rounded-[2rem] shadow-sm flex justify-center">
            {{ $announcements->links() }}
        </div>
    @endif
    </div>
</div>
@endsection
