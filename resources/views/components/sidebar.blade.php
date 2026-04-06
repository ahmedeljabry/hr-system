<aside 
    x-data="{ 
        collapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar_collapsed', this.collapsed);
        }
    }"
    :class="collapsed ? 'w-16' : 'w-64'"
    class="relative z-30 bg-surface border-inline-end border-gray-100 min-h-screen flex flex-col transition-all duration-300 shadow-sm hidden md:flex"
>
    <!-- Toggle Button -->
    <button @click="toggle()" class="absolute -inline-end-3 top-8 bg-white border border-gray-100 rounded-full p-1 text-gray-500 hover:text-primary z-40 transition-transform shadow-sm" :class="collapsed ? 'rotate-180' : ''">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Empty Brand Area -->
    <div class="h-16 flex items-center justify-center border-b border-gray-50 flex-shrink-0"></div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard*')">
            <x-slot name="icon">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </x-slot>
            {{ __('Dashboard') }}
        </x-sidebar-link>

        <x-sidebar-link href="{{ route('admin.clients.index') }}" :active="request()->routeIs('admin.clients.*')">
            <x-slot name="icon">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-slot>
            {{ __('Clients') }}
        </x-sidebar-link>
    </nav>

    <!-- Footer Actions -->
    <div class="px-3 py-4 border-t border-gray-50 flex-shrink-0 overflow-hidden">
        <div class="flex items-center justify-center mb-4 transition-opacity duration-300" :class="collapsed ? 'opacity-0 h-0 hidden' : 'opacity-100'">
            @if(app()->getLocale() == 'ar')
                <a href="/lang/en" class="text-xs uppercase font-bold text-gray-400 hover:text-primary transition-colors">EN</a>
            @else
                <a href="/lang/ar" class="text-xs uppercase font-bold text-gray-400 hover:text-primary transition-colors">عربي</a>
            @endif
        </div>

        <form method="POST" action="/logout" class="block w-full">
            @csrf
            <button type="submit" class="w-full flex items-center py-2 px-2 text-sm font-medium text-red-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors group" :class="collapsed ? 'justify-center' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span x-show="!collapsed" class="ms-3 whitespace-nowrap">{{ __('Logout') }}</span>
            </button>
        </form>
    </div>
</aside>
