@props([
    'title',
    'subtitle' => null,
    'backLink' => null,
])

<div {{ $attributes->merge(['class' => 'relative mb-8']) }}>
    <div class="bg-secondary rounded-[2rem] p-7 md:p-9 text-white relative overflow-hidden shadow-2xl border border-primary/10">
        <!-- Brand Signature Glows -->
        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-primary/10 rounded-full blur-[60px]"></div>
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-primary/5 rounded-full blur-[40px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            {{-- Title & Content Group --}}
            <div class="flex items-center gap-7">
                @if(isset($leading))
                    <div class="shrink-0 group-hover:scale-105 transition-transform duration-500">
                        {{ $leading }}
                    </div>
                @else
                    <div class="w-16 h-16 bg-primary/10 rounded-[1.5rem] flex items-center justify-center shrink-0 border border-primary/20 shadow-xl group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </div>
                @endif

                <div class="flex flex-col">
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight text-primary mb-1">
                        {{ $title }}
                    </h1>
                @if($subtitle)
                    <div class="text-gray-400 text-xs md:text-sm font-bold opacity-80">
                        {{ $subtitle }}
                    </div>
                @endif
            </div>
        </div>

            {{-- Actions / Back Button Area --}}
            <div class="flex items-center gap-4">
                @if(isset($actions))
                    {{ $actions }}
                @endif

                @if($backLink)
                    <a href="{{ $backLink }}" 
                       class="inline-flex items-center px-6 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-black rounded-xl transition-all duration-300 backdrop-blur-md group/back">
                        <svg class="w-4 h-4 me-2 group-hover/back:-translate-x-1 transition-transform rtl:group-hover/back:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('messages.back') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
