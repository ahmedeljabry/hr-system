<aside 
    x-data="{ 
        collapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar_collapsed', this.collapsed);
        }
    }"
    :class="collapsed ? 'w-20' : 'w-72'"
    class="relative z-50 bg-secondary border-e border-white/5 min-h-screen flex flex-col transition-all duration-500 shadow-[20px_0_50px_rgba(0,0,0,0.1)] hidden md:flex overflow-hidden"
>
    <!-- Background Decorative Gradients -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-primary/5 rounded-full -ml-24 -mb-24 blur-3xl pointer-events-none"></div>

    <!-- Toggle Button -->
    <button @click="toggle()" 
            class="absolute -right-3 top-10 bg-primary text-secondary rounded-xl p-1.5 shadow-lg shadow-primary/20 z-50 transition-all hover:scale-110 active:scale-95" 
            :class="collapsed ? 'rotate-180' : ''">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Brand Area -->
    <div class="h-24 flex items-center px-8 flex-shrink-0 relative">
        <div class="flex items-center gap-4 overflow-hidden">
            <div class="bg-primary/20 p-2.5 rounded-2xl border border-primary/20 shadow-inner flex-shrink-0">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-4 18V4"></path>
                </svg>
            </div>
            <span x-show="!collapsed" 
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 -translate-x-4"
                  x-transition:enter-end="opacity-100 translate-x-0"
                  class="text-xl font-outfit font-black text-white tracking-tight whitespace-nowrap uppercase">
                  {{ config('app.name') }}
            </span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar relative">
        <div class="mb-4 px-4" x-show="!collapsed">
            <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">{{ __('messages.menu') ?? 'Menu' }}</span>
        </div>

        <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </x-slot>
            {{ __('Dashboard') }}
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('admin.clients.index') }}" :active="request()->routeIs('admin.clients.*')">
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-slot>
            {{ __('Clients') }}
        </x-sidebar-link>
    </nav>

    <!-- Footer Actions -->
    <div class="p-6 border-t border-white/5 bg-secondary flex-shrink-0 relative overflow-hidden">
        <form method="POST" action="/logout" class="block w-full">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center p-3 text-sm font-bold text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-[1rem] transition-all group" 
                    :class="collapsed ? 'justify-center' : ''">
                <svg class="w-6 h-6 flex-shrink-0 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span x-show="!collapsed" class="ms-4 whitespace-nowrap uppercase tracking-widest text-[11px]">{{ __('Logout') }}</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Sidebar -->
<div x-show="mobileOpen" 
     x-transition:enter="transition ease-out duration-300" 
     x-transition:enter-start="{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }}" 
     x-transition:enter-end="translate-x-0" 
     class="fixed top-0 inset-y-0 z-50 w-72 bg-secondary md:hidden flex flex-col p-6 {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }}" 
     x-cloak>
    <div class="flex items-center justify-between mb-10">
        <span class="text-xl font-outfit font-black text-white tracking-tight uppercase">{{ config('app.name') }}</span>
        <button @click="mobileOpen = false" class="p-2 text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <nav class="flex-1 space-y-2">
        <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">
            <x-slot name="icon">D</x-slot>
            {{ __('Dashboard') }}
        </x-sidebar-link>
        <x-sidebar-link href="{{ route('admin.clients.index') }}" :active="request()->routeIs('admin.clients.*')">
            <x-slot name="icon">C</x-slot>
            {{ __('Clients') }}
        </x-sidebar-link>
    </nav>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.1); }
</style>
