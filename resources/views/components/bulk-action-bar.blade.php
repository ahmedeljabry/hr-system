<div 
    x-show="selected.length > 0"
    x-transition:enter="transition ease-out duration-400"
    x-transition:enter-start="opacity-0 -translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-full"
    class="absolute top-0 inset-x-0 bg-secondary/95 backdrop-blur-md z-[40] px-10 py-5 flex items-center justify-between text-white shadow-2xl rounded-t-[2.5rem] border-b border-white/10"
    style="display: none;"
>
    <div class="flex items-center gap-10">
        <div class="flex items-center gap-4 border-r border-white/10 pr-10 rtl:border-r-0 rtl:pr-0 rtl:border-l rtl:pl-10">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center font-black text-secondary shadow-lg">
                <span x-text="selected.length"></span>
            </div>
            <span class="text-xs font-black uppercase tracking-[0.2em] text-white/70">{{ __('messages.selected') }}</span>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Slots for external actions -->
            {{ $slot ?? '' }}
            
            <button class="group relative inline-flex items-center gap-3 px-8 py-3 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white font-black rounded-xl border border-red-500/20 hover:border-red-500 shadow-lg transition-all duration-300 overflow-hidden">
                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span class="text-xs uppercase tracking-widest">{{ __('messages.delete_selected') }}</span>
            </button>
        </div>
    </div>
    
    <button @click="selected = []" class="bg-white/5 hover:bg-white/10 text-white/50 hover:text-white p-3 rounded-xl border border-white/5 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
