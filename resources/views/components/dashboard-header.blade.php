@props([
    'title',
    'subtitle' => null,
    'buttonText' => null,
    'buttonLink' => null,
    'buttonIcon' => 'plus',
])

<div class="relative mb-10 mt-10">
    <div class="bg-[#142533] rounded-[2rem] p-8 md:p-10 text-white relative overflow-hidden shadow-2xl border border-white/5">
        <!-- Glow Effects -->
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-primary/20 rounded-full blur-[80px]"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 rounded-full blur-[60px]"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            {{-- Right side (Title & Subtitle) - RTL by default because of the layout dir --}}
            <div class="space-y-1">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight text-white">
                    {{ $title }}
                </h1>
                @if($subtitle)
                    <p class="text-gray-400 text-sm md:text-base font-medium opacity-90">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            {{-- Left side (Action Button) --}}
            @if($buttonText && $buttonLink)
                <div class="flex items-center">
                    <a href="{{ $buttonLink }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary/90 text-secondary text-sm font-black rounded-xl transition-all duration-300 hover:scale-[1.02] shadow-lg shadow-primary/20">
                        @if($buttonIcon === 'plus')
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        @endif
                        {{ $buttonText }}
                    </a>
                </div>
            @endif
            
            {{-- Slot for additional buttons if needed --}}
            @if(isset($actions))
                <div class="flex items-center gap-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
</div>
