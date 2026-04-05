<div 
    x-show="selected.length > 0"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-full"
    class="absolute top-0 inset-x-0 bg-primary z-20 px-6 py-4 flex items-center justify-between text-white shadow-md rounded-t-2xl"
    style="display: none;"
>
    <div class="flex items-center">
        <span class="font-semibold text-lg me-6">
            <span x-text="selected.length"></span> {{ __('selected') }}
        </span>
        
        <div class="flex space-x-3 rtl:space-x-reverse">
            <!-- Slots for external actions -->
            {{ $slot ?? '' }}
            <button class="px-4 py-1.5 bg-white/20 hover:bg-white/30 rounded border border-white/30 text-sm font-medium transition-colors">
                {{ __('Delete Selected') }}
            </button>
        </div>
    </div>
    
    <button @click="selected = []" class="text-white/80 hover:text-white p-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
