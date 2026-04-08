@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="w-full">
        <x-dashboard-sub-header 
            :title="__('Localization Decisions') ?? 'Localization Decisions'" 
            :subtitle="__('View official localization decisions and workforce requirements.')"
        />

        <div class="mt-8 bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">
                    {{ __('Available Decisions') }}
                </h3>
            </div>
            
            <div class="divide-y divide-gray-50">
                @forelse($decisions as $decision)
                <div class="p-8 hover:bg-gray-50/50 transition-all flex flex-col md:flex-row items-center justify-between gap-6 group">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-primary/10 text-secondary rounded-[1.5rem] flex items-center justify-center font-black text-xl shadow-sm group-hover:bg-primary/20 transition-colors">
                            {{ round($decision->saudi_percentage) }}%
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-secondary">{{ __('Saudization Requirement') }}</h4>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                                {{ $decision->jobs_count }} {{ __('Jobs Included') }} • {{ $decision->created_at->format('Y-m-d') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-10">
                        <div class="hidden lg:flex items-center gap-2">
                            @foreach($decision->jobs->take(3) as $job)
                                <span class="px-3 py-1 bg-gray-100 text-[10px] font-black uppercase text-gray-500 rounded-lg">
                                    {{ $job->job_title }}
                                </span>
                            @endforeach
                            @if($decision->jobs_count > 3)
                                <span class="text-[10px] font-black text-gray-300">+{{ $decision->jobs_count - 3 }}</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('client.localization.show', $decision->id) }}" class="inline-flex items-center px-6 py-3 bg-secondary text-primary text-xs font-black rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 group/btn">
                            {{ __('View Details') }}
                            <svg class="w-4 h-4 ms-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-24 text-center opacity-40">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4 4a2 2 0 01.586 1.414V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-lg font-bold text-secondary">{{ __('No decisions found.') }}</span>
                </div>
                @endforelse
            </div>
            
            @if($decisions->hasPages())
            <div class="p-8 border-t border-gray-50 bg-gray-50/10">
                {{ $decisions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
