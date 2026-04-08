@extends('layouts.app')

@section('content')
<div class="pt-8 pb-12">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('client.localization.index') }}" class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-secondary">{{ __('Decision Details') }}</h1>
                <p class="text-gray-400 font-bold tracking-widest uppercase text-xs mt-1">{{ __('Official Localization Requirement') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Percentage Card -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Required Saudi Percentage') }}</h3>
                        <div class="text-6xl font-black text-secondary">{{ $decision->saudi_percentage }}%</div>
                    </div>
                    <div class="w-24 h-24 rounded-full border-[8px] border-primary/20 border-t-primary animate-spin-slow"></div>
                </div>

                <!-- Jobs Table -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50">
                        <h3 class="text-xl font-black text-secondary">{{ __('Included Occupations') }}</h3>
                    </div>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-start text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Code') }}</th>
                                <th class="px-8 py-4 text-start text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Job Title') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($decision->jobs as $job)
                            <tr>
                                <td class="px-8 py-5">
                                    <span class="bg-gray-100 px-3 py-1 rounded-lg font-mono font-bold text-gray-600">{{ $job->occupation_code }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-bold text-secondary">{{ $job->job_title }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $job->job_title_ar }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Attachments -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-black text-secondary mb-6">{{ __('Official Documents') }}</h3>
                    
                    @if(!empty($decision->files))
                    <div class="space-y-3">
                        @foreach($decision->files as $file)
                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-secondary transition-all group">
                            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4 4a2 2 0 01.586 1.414V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-sm font-bold text-secondary truncate">{{ basename($file) }}</div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Download') }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="py-8 text-center text-gray-400 font-bold italic">
                        {{ __('No documents attached.') }}
                    </div>
                    @endif
                </div>

                <!-- Info Card -->
                <div class="bg-secondary rounded-[2.5rem] p-8 shadow-lg text-primary relative overflow-hidden group">
                    <svg class="absolute -inline-end-10 -bottom-10 w-40 h-40 opacity-10 group-hover:scale-110 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
                    <h4 class="text-lg font-black mb-2">{{ __('Compliance Notice') }}</h4>
                    <p class="text-sm font-bold leading-relaxed opacity-80">
                        {{ __('Please ensure your workforce aligns with these requirements to maintain compliance with labor regulations.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
@endsection
