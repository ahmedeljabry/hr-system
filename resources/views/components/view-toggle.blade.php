<div 
    x-data="{ 
        viewMode: localStorage.getItem('view_mode') || 'grid',
        setMode(mode) {
            this.viewMode = mode;
            localStorage.setItem('view_mode', mode);
            $dispatch('view-changed', mode);
        }
    }" 
    class="flex items-center p-1 bg-gray-100 rounded-lg shadow-inner"
>
    <button 
        @click="setMode('grid')" 
        :class="viewMode === 'grid' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
        class="px-3 py-1.5 rounded-md text-sm font-medium transition-all focus:outline-none flex flex-row items-center justify-center space-x-1"
    >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
        <span class="ms-1">{{ __('messages.grid') }}</span>
    </button>
    <button 
        @click="setMode('list')" 
        :class="viewMode === 'list' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
        class="px-3 py-1.5 rounded-md text-sm font-medium transition-all focus:outline-none flex flex-row items-center justify-center space-x-1"
    >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        <span class="ms-1">{{ __('messages.list') }}</span>
    </button>
</div>
